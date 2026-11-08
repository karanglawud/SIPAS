<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Message;
use App\Models\Tool;
use App\Models\ToolUnit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function create(Request $request, ?int $tool_id = null): View|RedirectResponse
    {
        if (! $tool_id) {
            return redirect()->route('catalog.index')->with('info', 'Silakan pilih alat yang ingin dipinjam terlebih dahulu dari katalog.');
        }

        $selectedTool = Tool::with('category')->findOrFail($tool_id);

        if ($selectedTool->available_stock <= 0) {
            return redirect()->route('catalog.index')->with('error', 'Maaf, stok alat "'.$selectedTool->name.'" saat ini sedang habis atau seluruh unit sedang dipinjam.');
        }

        return view('loans.create', compact('selectedTool'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'duration_days' => 'required|integer|min:1|max:14',
            'purpose' => 'required|string|max:1000',
            'proposal_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ], [
            'tool_id.required' => 'Pilih alat yang ingin Anda pinjam.',
            'quantity.required' => 'Jumlah unit peminjaman harus diisi.',
            'start_date.after_or_equal' => 'Tanggal pinjam minimal hari ini.',
            'duration_days.min' => 'Durasi peminjaman minimal 1 hari.',
            'duration_days.max' => 'Durasi peminjaman maksimal 14 hari.',
            'proposal_file.mimes' => 'Format file proposal harus berupa PDF, DOC, DOCX, atau Gambar (JPG/PNG).',
            'proposal_file.max' => 'Ukuran berkas proposal maksimal 10 MB.',
        ]);

        $tool = Tool::with('category.admins')->findOrFail($validated['tool_id']);

        if ($validated['quantity'] > $tool->available_stock) {
            return back()->withInput()->with('error', 'Jumlah unit yang diajukan ('.$validated['quantity'].') melebihi stok tersedia saat ini ('.$tool->available_stock.' unit).');
        }

        $proposalPath = null;
        $proposalFilename = null;
        if ($request->hasFile('proposal_file')) {
            $file = $request->file('proposal_file');
            $proposalFilename = $file->getClientOriginalName();
            $proposalPath = $file->store('proposals', 'public');
        }

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addDays((int) $validated['duration_days']);

        $year = date('Y');
        $prefix = "SIPAS-{$year}-";

        $lastTicket = Loan::where('ticket_number', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->value('ticket_number');

        $nextSeq = 101;
        if ($lastTicket) {
            $lastNum = (int) substr(strrchr($lastTicket, '-'), 1);
            $nextSeq = max($lastNum + 1, 101);
        }

        while (Loan::where('ticket_number', $prefix.str_pad((string) $nextSeq, 3, '0', STR_PAD_LEFT))->exists()) {
            $nextSeq++;
        }

        $ticketNumber = $prefix.str_pad((string) $nextSeq, 3, '0', STR_PAD_LEFT);

        $loan = Loan::create([
            'ticket_number' => $ticketNumber,
            'user_id' => Auth::id(),
            'tool_id' => $tool->id,
            'quantity' => $validated['quantity'],
            'start_date' => $startDate->format('Y-m-d'),
            'duration_days' => $validated['duration_days'],
            'end_date' => $endDate->format('Y-m-d'),
            'purpose' => $validated['purpose'],
            'proposal_path' => $proposalPath,
            'proposal_filename' => $proposalFilename,
            'status' => 'menunggu',
            'assigned_units' => null,
            'pj_notes' => null,
        ]);

        $adminPj = $tool->category->admins->first() ?? User::where('role', 'admin')->first();
        if ($adminPj) {
            Message::create([
                'sender_id' => $adminPj->id,
                'recipient_id' => Auth::id(),
                'category_id' => $tool->category_id,
                'loan_id' => $loan->id,
                'type' => 'info',
                'message' => 'Pengajuan peminjaman '.$tool->name.' ('.$loan->quantity.' unit) telah terdaftar dengan No. Peminjaman '.$ticketNumber.'. Pengajuan sedang ditinjau oleh tim sarana.',
                'is_read' => false,
            ]);
        }

        return redirect()->route('loans.index')->with('success', 'Permohonan peminjaman berhasil diajukan dengan No. Peminjaman '.$ticketNumber.'. Pantau perkembangan status melalui menu Riwayat atau Pesan.');
    }

    public function index(): View
    {
        $loans = Loan::with(['tool.category', 'approver'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('loans.index', compact('loans'));
    }

    public function printTicket(Loan $loan): View
    {
        if ($loan->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak untuk mencetak bukti peminjaman ini.');
        }

        $loan->load(['user', 'tool.category', 'approver']);

        $backRoute = route('loans.index');
        $backLabel = 'Kembali ke Riwayat';

        return view('loans.print', compact('loan', 'backRoute', 'backLabel'));
    }

    public function printHistory(Request $request): View
    {
        $user = Auth::user();
        $query = Loan::with(['tool.category', 'approver'])
            ->where('user_id', $user->id)
            ->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $loans = $query->get();

        $stats = [
            'total' => $loans->count(),
            'selesai' => $loans->where('status', 'selesai')->count(),
            'berjalan' => $loans->whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'menunggu' => $loans->where('status', 'menunggu')->count(),
            'total_denda' => (int) $loans->sum('fine_amount'),
        ];

        return view('loans.print-history', compact('loans', 'user', 'stats'));
    }

    public function cancel(Request $request, Loan $loan): RedirectResponse
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk membatalkan pengajuan peminjaman ini.');
        }

        if (! in_array($loan->status, ['menunggu', 'disetujui'])) {
            return back()->with('error', 'Peminjaman tidak dapat dibatalkan karena status saat ini: '.$loan->status_label.'.');
        }

        $nowFormatted = Carbon::now()->translatedFormat('d M Y, H:i');
        $userName = Auth::user()->name;
        $userId = Auth::id();

        try {
            DB::transaction(function () use ($loan, $nowFormatted, $userName) {
                $lockedLoan = Loan::where('id', $loan->id)->lockForUpdate()->firstOrFail();

                if (! in_array($lockedLoan->status, ['menunggu', 'disetujui'])) {
                    throw new \RuntimeException('Peminjaman tidak dapat dibatalkan karena status saat ini: '.$lockedLoan->status_label);
                }

                if ($lockedLoan->status === 'disetujui' && ! empty($lockedLoan->assigned_units)) {
                    foreach ($lockedLoan->assigned_units as $unitCode) {
                        ToolUnit::where('unit_code', $unitCode)->lockForUpdate()->update([
                            'status' => 'tersedia',
                        ]);
                    }
                    $tool = Tool::where('id', $lockedLoan->tool_id)->lockForUpdate()->first();
                    $tool?->increment('available_stock', $lockedLoan->quantity);
                }

                $lockedLoan->update([
                    'status' => 'dibatalkan',
                    'pj_notes' => trim(($lockedLoan->pj_notes ? $lockedLoan->pj_notes.' | ' : '').'Dibatalkan oleh peminjam ('.$userName.') pada '.$nowFormatted),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $loan->refresh();
        $adminPj = $loan->tool->category->admins->first() ?? User::where('role', 'admin')->first();

        if ($adminPj) {
            Message::create([
                'sender_id' => $userId,
                'recipient_id' => $adminPj->id,
                'category_id' => $loan->tool->category_id,
                'loan_id' => $loan->id,
                'type' => 'warning',
                'message' => 'Peminjam ('.$userName.') telah MEMBATALKAN permohonan peminjaman '.$loan->tool->name.' (No. Peminjaman '.$loan->ticket_number.').',
                'is_read' => false,
            ]);
        }

        Message::create([
            'sender_id' => $adminPj ? $adminPj->id : $userId,
            'recipient_id' => $userId,
            'category_id' => $loan->tool->category_id,
            'loan_id' => $loan->id,
            'type' => 'info',
            'message' => 'Permohonan peminjaman '.$loan->tool->name.' (No. Peminjaman '.$loan->ticket_number.') telah berhasil dibatalkan.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Permohonan peminjaman No. Peminjaman '.$loan->ticket_number.' berhasil dibatalkan.');
    }
}
