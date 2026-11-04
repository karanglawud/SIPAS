<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Message;
use App\Models\ToolUnit;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoanController extends Controller
{
    private function checkAdmin(): ?RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        return null;
    }

    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

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
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $tool = $loan->tool;
        $quantity = $loan->quantity;

        // Custom selected unit codes or auto-assignment
        $assignedCodes = [];
        $selectedUnitCodes = $request->input('unit_codes', []);

        if (is_array($selectedUnitCodes) && count($selectedUnitCodes) > 0) {
            if (count($selectedUnitCodes) !== $quantity) {
                return back()->with('error', 'Jumlah unit fisik yang dipilih ('.count($selectedUnitCodes).') harus sama persis dengan kuantitas permohonan ('.$quantity.' unit).');
            }

            $units = ToolUnit::where('tool_id', $tool->id)
                ->whereIn('unit_code', $selectedUnitCodes)
                ->where('status', 'tersedia')
                ->get();

            if ($units->count() !== $quantity) {
                return back()->with('error', 'Beberapa unit fisik yang dipilih sudah tidak tersedia atau tidak valid.');
            }

            foreach ($units as $unit) {
                $unit->update(['status' => 'dipinjam']);
                $assignedCodes[] = $unit->unit_code;
            }
        } else {
            // Auto assign first available units
            $availableUnits = ToolUnit::where('tool_id', $tool->id)
                ->where('status', 'tersedia')
                ->limit($quantity)
                ->get();

            if ($availableUnits->count() < $quantity) {
                return back()->with('error', 'Gagal menyetujui: Unit alat tersedia ('.$availableUnits->count().') tidak mencukupi permintaan ('.$quantity.').');
            }

            foreach ($availableUnits as $unit) {
                $unit->update(['status' => 'dipinjam']);
                $assignedCodes[] = $unit->unit_code;
            }
        }

        $tool->decrement('available_stock', $quantity);

        $pickupNotes = $request->input('pj_notes', 'Disetujui oleh '.Auth::user()->name.'. Harap cek kondisi fisik sebelum meninggalkan ruang sarana.');

        $loan->update([
            'status' => 'disetujui',
            'assigned_units' => $assignedCodes,
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'pj_notes' => $pickupNotes,
        ]);

        if ($request->boolean('send_notification', true)) {
            Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $loan->user_id,
                'category_id' => $tool->category_id,
                'loan_id' => $loan->id,
                'type' => 'approval',
                'message' => 'Permohonan peminjaman No. Tiket '.$loan->ticket_number.' telah DISETUJUI. Kode unit fisik yang dialokasikan: '.implode(', ', $assignedCodes).'. Catatan Pengambilan: '.$pickupNotes.' (Lokasi: '.$tool->location.').',
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Pengajuan No. '.$loan->ticket_number.' berhasil disetujui. Unit fisik: '.implode(', ', $assignedCodes));
    }

    public function handover(Request $request, Loan $loan): RedirectResponse
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

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
            'message' => 'Alat '.$loan->tool->name.' (No. Tiket '.$loan->ticket_number.') telah resmi diserahterimakan ke Anda. Harap merawat alat dan mengembalikannya sebelum '.$loan->end_date->translatedFormat('d M Y').' pukul 15.30 WIB.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Status peminjaman No. '.$loan->ticket_number.' telah diubah menjadi "Sedang Dipinjam" (Fisik telah diserahkan).');
    }

    public function reject(Request $request, Loan $loan): RedirectResponse
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
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
            'message' => 'Permohonan peminjaman No. Tiket '.$loan->ticket_number.' belum dapat disetujui. Alasan: '.$reason,
            'is_read' => false,
        ]);

        return back()->with('info', 'Pengajuan No. '.$loan->ticket_number.' telah ditolak.');
    }

    public function complete(Request $request, Loan $loan): RedirectResponse
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $request->validate([
            'return_condition' => 'required|in:baik,rusak,hilang',
            'fine_status' => 'required|in:none,belum_lunas,lunas',
            'return_notes' => 'nullable|string|max:500',
        ]);

        $tool = $loan->tool;
        $returnDate = Carbon::now();
        $condition = $request->input('return_condition');
        $fineStatus = $request->input('fine_status');

        $fineAmount = 0;
        if ($returnDate->gt($loan->end_date)) {
            $diffDays = $returnDate->diffInDays($loan->end_date);
            if ($diffDays > 0) {
                $fineAmount = $diffDays * $tool->fine_per_day * $loan->quantity;
            }
        }

        if ($fineAmount === 0) {
            $fineStatus = 'none';
        }

        if (! empty($loan->assigned_units)) {
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

        $notes = $request->input('return_notes', 'Pengembalian tercatat dengan kondisi: '.strtoupper($condition));
        if ($fineAmount > 0) {
            $notes .= ' (Terlambat, denda: Rp '.number_format($fineAmount, 0, ',', '.').' - Status: '.strtoupper($fineStatus).')';
        }

        $loan->update([
            'status' => 'selesai',
            'actual_return_date' => $returnDate->format('Y-m-d'),
            'return_condition' => $condition,
            'fine_amount' => $fineAmount,
            'fine_status' => $fineStatus,
            'pj_notes' => $loan->pj_notes.' | Pengembalian: '.$notes,
        ]);

        $msgText = 'Pengembalian alat untuk No. Tiket '.$loan->ticket_number.' telah diterima oleh '.Auth::user()->name.' dengan kondisi fisik: '.strtoupper($condition).'.';
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

    public function sendMessage(Request $request, Loan $loan): RedirectResponse
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

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
