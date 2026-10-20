<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Message;
use App\Models\Tool;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function create(Request $request, $tool_id = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan masuk terlebih dahulu untuk mengajukan peminjaman.');
        }

        $selectedTool = null;
        if ($tool_id) {
            $selectedTool = Tool::with('category')->findOrFail($tool_id);
        }

        $tools = Tool::with('category')->where('available_stock', '>', 0)->get();

        return view('loans.create', compact('selectedTool', 'tools'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

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
            return back()->withInput()->with('error', 'Jumlah unit yang diajukan (' . $validated['quantity'] . ') melebihi stok tersedia saat ini (' . $tool->available_stock . ' unit).');
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

        $ticketNumber = 'SIPAS-' . date('Y') . '-' . str_pad(Loan::count() + 101, 3, '0', STR_PAD_LEFT);

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
                'message' => 'Pengajuan peminjaman ' . $tool->name . ' (' . $loan->quantity . ' unit) telah terdaftar dengan Nomor Tiket ' . $ticketNumber . '. Pengajuan sedang ditinjau oleh tim sarana.',
                'is_read' => false,
            ]);
        }

        return redirect()->route('loans.index')->with('success', 'Permohonan peminjaman berhasil diajukan dengan No. Tiket ' . $ticketNumber . '. Pantau perkembangan status melalui menu Riwayat atau Pesan.');
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $loans = Loan::with(['tool.category', 'approver'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('loans.index', compact('loans'));
    }

    public function printTicket(Loan $loan)
    {
        $loan->load(['user', 'tool.category', 'approver']);

        return view('loans.print', compact('loan'));
    }
}
