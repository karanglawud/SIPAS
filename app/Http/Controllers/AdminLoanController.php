<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Message;
use App\Models\ToolUnit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoanController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        $admin = Auth::user();
        $query = Loan::with(['user', 'tool.category', 'approver'])->latest();

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
            $query->whereIn('status', ['selesai', 'ditolak']);
        }

        $loans = $query->paginate(15);

        $baseQuery = Loan::query();
        if ($admin->category_scope && $admin->category_scope !== 'all') {
            $baseQuery->whereHas('tool.category', function ($q) use ($admin) {
                $q->where('slug', $admin->category_scope);
            });
        }

        $pendingCount = (clone $baseQuery)->where('status', 'menunggu')->count();
        $activeCount = (clone $baseQuery)->whereIn('status', ['disetujui', 'dipinjam'])->count();
        $completedCount = (clone $baseQuery)->whereIn('status', ['selesai', 'ditolak'])->count();

        return view('admin.loans.index', compact('loans', 'statusTab', 'pendingCount', 'activeCount', 'completedCount', 'admin'));
    }

    public function approve(Request $request, Loan $loan)
    {
        $tool = $loan->tool;

        $availableUnits = ToolUnit::where('tool_id', $tool->id)
            ->where('status', 'tersedia')
            ->limit($loan->quantity)
            ->get();

        if ($availableUnits->count() < $loan->quantity) {
            return back()->with('error', 'Gagal menyetujui: Unit alat tersedia (' . $availableUnits->count() . ') tidak mencukupi permintaan (' . $loan->quantity . ').');
        }

        $assignedCodes = [];
        foreach ($availableUnits as $unit) {
            $unit->update(['status' => 'dipinjam']);
            $assignedCodes[] = $unit->unit_code;
        }

        $tool->decrement('available_stock', $loan->quantity);

        $pjNotes = $request->input('pj_notes', 'Disetujui oleh ' . Auth::user()->name . '. Harap cek kondisi fisik sebelum meninggalkan ruang sarana.');

        $loan->update([
            'status' => 'disetujui',
            'assigned_units' => $assignedCodes,
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'pj_notes' => $pjNotes,
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $loan->user_id,
            'category_id' => $tool->category_id,
            'loan_id' => $loan->id,
            'type' => 'approval',
            'message' => 'Permohonan peminjaman No. Tiket ' . $loan->ticket_number . ' telah DISETUJUI. Kode unit fisik yang dialokasikan: ' . implode(', ', $assignedCodes) . '. Silakan mengambil alat di ruang ' . $tool->location . '.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Pengajuan No. ' . $loan->ticket_number . ' berhasil disetujui. Unit fisik ter-assign: ' . implode(', ', $assignedCodes));
    }

    public function reject(Request $request, Loan $loan)
    {
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
            'pj_notes' => 'DITOLAK: ' . $reason,
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $loan->user_id,
            'category_id' => $loan->tool->category_id,
            'loan_id' => $loan->id,
            'type' => 'warning',
            'message' => 'Permohonan peminjaman No. Tiket ' . $loan->ticket_number . ' belum dapat disetujui. Alasan: ' . $reason,
            'is_read' => false,
        ]);

        return back()->with('info', 'Pengajuan No. ' . $loan->ticket_number . ' telah ditolak.');
    }

    public function complete(Request $request, Loan $loan)
    {
        $request->validate([
            'return_condition' => 'required|in:baik,rusak,hilang',
            'return_notes' => 'nullable|string|max:500',
        ]);

        $tool = $loan->tool;
        $returnDate = Carbon::now();
        $condition = $request->input('return_condition');

        $fineAmount = 0;
        $fineStatus = 'none';
        if ($returnDate->gt($loan->end_date)) {
            $diffDays = $returnDate->diffInDays($loan->end_date);
            if ($diffDays > 0) {
                $fineAmount = $diffDays * $tool->fine_per_day * $loan->quantity;
                $fineStatus = 'belum_lunas';
            }
        }

        if (!empty($loan->assigned_units)) {
            foreach ($loan->assigned_units as $unitCode) {
                $unit = ToolUnit::where('unit_code', $unitCode)->first();
                if ($unit) {
                    $newStatus = ($condition === 'baik') ? 'tersedia' : (($condition === 'rusak') ? 'rusak' : 'hilang');
                    $unit->update([
                        'status' => $newStatus,
                        'condition' => $condition,
                    ]);
                }
            }

            if ($condition === 'baik') {
                $tool->increment('available_stock', $loan->quantity);
            }
        }

        $notes = $request->input('return_notes', 'Pengembalian tercatat dengan kondisi: ' . strtoupper($condition));
        if ($fineAmount > 0) {
            $notes .= ' (Terlambat, denda: Rp ' . number_format($fineAmount, 0, ',', '.') . ')';
        }

        $loan->update([
            'status' => 'selesai',
            'actual_return_date' => $returnDate->format('Y-m-d'),
            'return_condition' => $condition,
            'fine_amount' => $fineAmount,
            'fine_status' => $fineStatus,
            'pj_notes' => $loan->pj_notes . ' | Pengembalian: ' . $notes,
        ]);

        $msgText = 'Pengembalian alat untuk No. Tiket ' . $loan->ticket_number . ' telah diterima oleh ' . Auth::user()->name . ' dengan kondisi fisik: ' . strtoupper($condition) . '.';
        if ($fineAmount > 0) {
            $msgText .= ' Terdapat denda keterlambatan sebesar Rp ' . number_format($fineAmount, 0, ',', '.') . ' yang perlu diselesaikan di ruang sarana.';
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

        return back()->with('success', 'Pengembalian alat No. ' . $loan->ticket_number . ' berhasil diproses.');
    }
}
