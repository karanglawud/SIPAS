<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Message;
use App\Models\Tool;
use App\Models\ToolUnit;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminLoanController extends Controller
{
    private function authorizeLoanScope(Loan $loan): void
    {
        $loan->loadMissing('tool.category');
        if (! Auth::user()->canManageCategory($loan->tool->category->slug)) {
            abort(403, 'Anda tidak memiliki hak akses mengelola permohonan pada kategori ini.');
        }
    }

    public function index(Request $request): View
    {
        $admin = Auth::user();
        $query = Loan::with(['user', 'tool.category', 'tool.availableUnits', 'approver'])->latest();

        if ($admin->category_scope && $admin->category_scope !== 'all') {
            $query->whereHas('tool.category', function ($q) use ($admin) {
                $q->where('slug', $admin->category_scope);
            });
        }

        $statusTab = $request->get('status', 'menunggu');
        if ($statusTab === 'menunggu') {
            $query->where('status', 'menunggu');
        } elseif ($statusTab === 'berjalan') {
            $query->whereIn('status', ['disetujui', 'dipinjam']);
        } elseif ($statusTab === 'selesai') {
            $query->whereIn('status', ['selesai', 'ditolak', 'dibatalkan']);
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('ticket_number', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('identifier', 'like', "%{$q}%")
                            ->orWhere('class_or_dept', 'like', "%{$q}%");
                    })
                    ->orWhereHas('tool', function ($t) use ($q) {
                        $t->where('name', 'like', "%{$q}%")
                            ->orWhere('code', 'like', "%{$q}%");
                    });
            });
        }

        $loans = $query->paginate(12);

        $baseQuery = Loan::query();
        if ($admin->category_scope && $admin->category_scope !== 'all') {
            $baseQuery->whereHas('tool.category', function ($q) use ($admin) {
                $q->where('slug', $admin->category_scope);
            });
        }

        $pendingCount = (clone $baseQuery)->where('status', 'menunggu')->count();
        $activeCount = (clone $baseQuery)->whereIn('status', ['disetujui', 'dipinjam'])->count();
        $completedCount = (clone $baseQuery)->whereIn('status', ['selesai', 'ditolak', 'dibatalkan'])->count();

        return view('admin.loans.index', compact(
            'loans',
            'statusTab',
            'pendingCount',
            'activeCount',
            'completedCount',
            'admin'
        ));
    }

    public function approve(Request $request, Loan $loan): RedirectResponse
    {
        $this->authorizeLoanScope($loan);

        if ($loan->status !== 'menunggu') {
            return back()->with('error', 'Hanya permohonan dengan status Menunggu yang dapat disetujui.');
        }

        $pickupNotes = $request->input('pj_notes', 'Disetujui oleh '.Auth::user()->name.'. Harap cek kondisi fisik sebelum meninggalkan ruang sarana.');
        $sendNotification = $request->boolean('send_notification', true);
        $selectedUnitCodes = $request->input('unit_codes', []);
        $adminId = Auth::id();

        try {
            $assignedCodes = DB::transaction(function () use ($loan, $selectedUnitCodes, $pickupNotes, $adminId) {
                $lockedLoan = Loan::where('id', $loan->id)->lockForUpdate()->firstOrFail();
                if ($lockedLoan->status !== 'menunggu') {
                    throw new \RuntimeException('Permohonan tidak dapat disetujui karena status saat ini: '.$lockedLoan->status_label);
                }

                $tool = Tool::where('id', $lockedLoan->tool_id)->lockForUpdate()->firstOrFail();
                $quantity = $lockedLoan->quantity;

                if ($tool->available_stock < $quantity) {
                    throw new \RuntimeException('Gagal menyetujui: Stok alat tersedia ('.$tool->available_stock.') tidak mencukupi permintaan ('.$quantity.' unit).');
                }

                $assignedCodes = [];

                if (is_array($selectedUnitCodes) && count($selectedUnitCodes) > 0) {
                    if (count($selectedUnitCodes) !== $quantity) {
                        throw new \RuntimeException('Jumlah unit fisik yang dipilih ('.count($selectedUnitCodes).') harus sama persis dengan kuantitas permohonan ('.$quantity.' unit).');
                    }

                    $units = ToolUnit::where('tool_id', $tool->id)
                        ->whereIn('unit_code', $selectedUnitCodes)
                        ->where('status', 'tersedia')
                        ->lockForUpdate()
                        ->get();

                    if ($units->count() !== $quantity) {
                        throw new \RuntimeException('Beberapa unit fisik yang dipilih sudah tidak tersedia atau tidak valid.');
                    }

                    foreach ($units as $unit) {
                        $unit->update(['status' => 'dipinjam']);
                        $assignedCodes[] = $unit->unit_code;
                    }
                } else {
                    $availableUnits = ToolUnit::where('tool_id', $tool->id)
                        ->where('status', 'tersedia')
                        ->lockForUpdate()
                        ->limit($quantity)
                        ->get();

                    if ($availableUnits->count() < $quantity) {
                        throw new \RuntimeException('Gagal menyetujui: Unit alat tersedia ('.$availableUnits->count().') tidak mencukupi permintaan ('.$quantity.').');
                    }

                    foreach ($availableUnits as $unit) {
                        $unit->update(['status' => 'dipinjam']);
                        $assignedCodes[] = $unit->unit_code;
                    }
                }

                $tool->decrement('available_stock', $quantity);

                $lockedLoan->update([
                    'status' => 'disetujui',
                    'assigned_units' => $assignedCodes,
                    'approved_by' => $adminId,
                    'approved_at' => Carbon::now(),
                    'pj_notes' => $pickupNotes,
                ]);

                return $assignedCodes;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $loan->refresh();
        $tool = $loan->tool;

        if ($sendNotification) {
            Message::create([
                'sender_id' => $adminId,
                'recipient_id' => $loan->user_id,
                'category_id' => $tool->category_id,
                'loan_id' => $loan->id,
                'type' => 'approval',
                'message' => 'Permohonan peminjaman No. Peminjaman '.$loan->ticket_number.' telah DISETUJUI. Kode unit fisik yang dialokasikan: '.implode(', ', $assignedCodes).'. Catatan Pengambilan: '.$pickupNotes.' (Lokasi: '.$tool->location.').',
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Pengajuan No. '.$loan->ticket_number.' berhasil disetujui. Unit fisik: '.implode(', ', $assignedCodes));
    }

    public function handover(Request $request, Loan $loan): RedirectResponse
    {
        $this->authorizeLoanScope($loan);

        if ($loan->status !== 'disetujui') {
            return back()->with('error', 'Serah terima alat hanya dapat dilakukan untuk permohonan yang berstatus Disetujui.');
        }

        $now = Carbon::now();
        $notes = $request->input('handover_notes', 'Alat diserahterimakan pada '.$now->translatedFormat('d M Y, H:i').' oleh '.Auth::user()->name);

        $loan->update([
            'status' => 'dipinjam',
            'pj_notes' => $loan->pj_notes.' | Serah Terima: '.$notes,
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $loan->user_id,
            'category_id' => $loan->tool->category_id,
            'loan_id' => $loan->id,
            'type' => 'info',
            'message' => 'Alat '.$loan->tool->name.' (No. Peminjaman '.$loan->ticket_number.') telah resmi diserahterimakan ke Anda. Harap merawat alat dan mengembalikannya sebelum '.$loan->end_date->translatedFormat('d M Y').' pukul 15.30 WIB.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Status peminjaman No. '.$loan->ticket_number.' telah diubah menjadi "Sedang Dipinjam" (Fisik telah diserahkan).');
    }

    public function reject(Request $request, Loan $loan): RedirectResponse
    {
        $this->authorizeLoanScope($loan);

        if ($loan->status !== 'menunggu') {
            return back()->with('error', 'Hanya permohonan dengan status Menunggu yang dapat ditolak.');
        }

        $request->validate([
            'reject_reason' => 'required|string|max:500',
        ], [
            'reject_reason.required' => 'Wajib menyertakan catatan alasan penolakan.',
        ]);

        $reason = $request->input('reject_reason');

        $loan->update([
            'status' => 'ditolak',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'pj_notes' => 'DITOLAK: '.$reason,
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $loan->user_id,
            'category_id' => $loan->tool->category_id,
            'loan_id' => $loan->id,
            'type' => 'warning',
            'message' => 'Permohonan peminjaman No. Peminjaman '.$loan->ticket_number.' belum dapat disetujui. Alasan: '.$reason,
            'is_read' => false,
        ]);

        return back()->with('info', 'Pengajuan No. '.$loan->ticket_number.' telah ditolak.');
    }

    public function complete(Request $request, Loan $loan): RedirectResponse
    {
        $this->authorizeLoanScope($loan);

        if ($loan->status !== 'dipinjam') {
            return back()->with('error', 'Hanya peminjaman dengan status Sedang Dipinjam yang dapat diselesaikan.');
        }

        $request->validate([
            'return_condition' => 'required|in:baik,rusak,hilang',
            'fine_status' => 'required|in:none,belum_lunas,lunas',
            'return_notes' => 'nullable|string|max:500',
        ]);

        $condition = $request->input('return_condition');
        $fineStatus = $request->input('fine_status');
        $returnDate = Carbon::now();
        $notesInput = $request->input('return_notes', 'Pengembalian tercatat dengan kondisi: '.strtoupper($condition));

        $fineAmount = 0;
        $tool = $loan->tool;

        try {
            DB::transaction(function () use ($loan, $condition, &$fineStatus, &$fineAmount, &$tool, $returnDate, $notesInput) {
                $lockedLoan = Loan::where('id', $loan->id)->lockForUpdate()->firstOrFail();
                if ($lockedLoan->status !== 'dipinjam') {
                    throw new \RuntimeException('Hanya peminjaman dengan status Sedang Dipinjam yang dapat diselesaikan.');
                }

                $tool = Tool::where('id', $lockedLoan->tool_id)->lockForUpdate()->firstOrFail();

                if ($returnDate->gt($lockedLoan->end_date)) {
                    $diffDays = $returnDate->diffInDays($lockedLoan->end_date);
                    if ($diffDays > 0) {
                        $fineAmount = $diffDays * $tool->fine_per_day * $lockedLoan->quantity;
                    }
                }

                if ($fineAmount === 0) {
                    $fineStatus = 'none';
                }

                if (! empty($lockedLoan->assigned_units)) {
                    foreach ($lockedLoan->assigned_units as $unitCode) {
                        $unit = ToolUnit::where('unit_code', $unitCode)->lockForUpdate()->first();
                        if ($unit) {
                            $newStatus = ($condition === 'baik') ? 'tersedia' : (($condition === 'rusak') ? 'rusak' : 'hilang');
                            $unit->update([
                                'status' => $newStatus,
                                'condition' => $condition,
                            ]);
                        }
                    }

                    if ($condition === 'baik') {
                        $tool->increment('available_stock', $lockedLoan->quantity);
                    }
                }

                $notes = $notesInput;
                if ($fineAmount > 0) {
                    $notes .= ' (Terlambat, denda: Rp '.number_format($fineAmount, 0, ',', '.').' - Status: '.strtoupper($fineStatus).')';
                }

                $lockedLoan->update([
                    'status' => 'selesai',
                    'actual_return_date' => $returnDate->format('Y-m-d'),
                    'return_condition' => $condition,
                    'fine_amount' => $fineAmount,
                    'fine_status' => $fineStatus,
                    'pj_notes' => $lockedLoan->pj_notes.' | Pengembalian: '.$notes,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $loan->refresh();

        $msgText = 'Pengembalian alat untuk No. Peminjaman '.$loan->ticket_number.' telah diterima oleh '.Auth::user()->name.' dengan kondisi fisik: '.strtoupper($condition).'.';
        if ($fineAmount > 0) {
            $msgText .= ' Terdapat denda keterlambatan sebesar Rp '.number_format($fineAmount, 0, ',', '.').' (Status denda: '.strtoupper($fineStatus).').';
        }

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $loan->user_id,
            'category_id' => $tool->category_id,
            'loan_id' => $loan->id,
            'type' => 'info',
            'message' => $msgText,
            'is_read' => false,
        ]);

        return back()->with('success', 'Pengembalian alat No. '.$loan->ticket_number.' berhasil diproses.');
    }

    public function printTicket(Loan $loan): View
    {
        $this->authorizeLoanScope($loan);

        $loan->load(['user', 'tool.category', 'approver']);

        $backRoute = route('admin.loans.index');
        $backLabel = 'Kembali ke Panel Admin';

        return view('loans.print', compact('loan', 'backRoute', 'backLabel'));
    }

    public function printReport(Request $request): View
    {
        $admin = Auth::user();
        $query = Loan::with(['user', 'tool.category', 'approver'])->latest();

        if ($admin->category_scope && $admin->category_scope !== 'all') {
            $query->whereHas('tool.category', function ($q) use ($admin) {
                $q->where('slug', $admin->category_scope);
            });
        }

        $statusTab = $request->get('status', 'all');
        if ($statusTab === 'menunggu') {
            $query->where('status', 'menunggu');
        } elseif ($statusTab === 'berjalan') {
            $query->whereIn('status', ['disetujui', 'dipinjam']);
        } elseif ($statusTab === 'selesai') {
            $query->whereIn('status', ['selesai', 'ditolak', 'dibatalkan']);
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('ticket_number', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('identifier', 'like', "%{$q}%")
                            ->orWhere('class_or_dept', 'like', "%{$q}%");
                    })
                    ->orWhereHas('tool', function ($t) use ($q) {
                        $t->where('name', 'like', "%{$q}%")
                            ->orWhere('code', 'like', "%{$q}%");
                    });
            });
        }

        $loans = $query->get();

        $stats = [
            'total' => $loans->count(),
            'menunggu' => $loans->where('status', 'menunggu')->count(),
            'berjalan' => $loans->whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'selesai' => $loans->where('status', 'selesai')->count(),
            'total_denda' => (int) $loans->sum('fine_amount'),
        ];

        $backRoute = route('admin.loans.index');
        $backLabel = 'Kembali ke Panel Admin';

        return view('admin.loans.print-report', compact('loans', 'admin', 'stats', 'statusTab', 'backRoute', 'backLabel'));
    }

    public function sendMessage(Request $request, Loan $loan): RedirectResponse
    {
        $this->authorizeLoanScope($loan);

        $validated = $request->validate([
            'type' => 'required|in:info,warning,approval',
            'message' => 'required|string|max:2000',
        ], [
            'message.required' => 'Pesan pemberitahuan wajib diisi.',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $loan->user_id,
            'category_id' => $loan->tool->category_id,
            'loan_id' => $loan->id,
            'type' => $validated['type'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Pemberitahuan khusus telah berhasil dikirim ke peminjam ('.$loan->user->name.').');
    }
}
