<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'tool_id',
        'quantity',
        'start_date',
        'duration_days',
        'end_date',
        'purpose',
        'proposal_path',
        'proposal_filename',
        'status',
        'assigned_units',
        'approved_by',
        'approved_at',
        'pj_notes',
        'actual_return_date',
        'return_condition',
        'fine_amount',
        'fine_status',
        'last_reminder_sent_at',
        'overdue_days',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_return_date' => 'date',
        'approved_at' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
        'overdue_days' => 'integer',
        'assigned_units' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class)->withTrashed();
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getLoanNumberAttribute(): string
    {
        return $this->ticket_number;
    }

    public function getIsOverdueAttribute(): bool
    {
        if (! in_array($this->status, ['disetujui', 'dipinjam'])) {
            return false;
        }

        return Carbon::now()->startOfDay()->gt($this->end_date->startOfDay());
    }

    public function getComputedOverdueDaysAttribute(): int
    {
        if (! $this->is_overdue) {
            return 0;
        }

        return abs((int) Carbon::now()->startOfDay()->diffInDays($this->end_date->startOfDay()));
    }

    public function getComputedFineAttribute(): int
    {
        if ($this->status === 'selesai') {
            return (int) $this->fine_amount;
        }

        $days = $this->computed_overdue_days;
        if ($days <= 0 || ! $this->tool) {
            return 0;
        }

        return $days * (int) $this->tool->fine_per_day * (int) $this->quantity;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'bg-amber-100 text-amber-800 border-amber-300',
            'disetujui' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'dipinjam' => 'bg-blue-100 text-blue-800 border-blue-300',
            'selesai' => 'bg-slate-100 text-slate-800 border-slate-300',
            'ditolak' => 'bg-rose-100 text-rose-800 border-rose-300',
            'dibatalkan' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu Verifikasi PJ',
            'disetujui' => 'Disetujui (Siap Diambil)',
            'dipinjam' => 'Sedang Dipinjam',
            'selesai' => 'Selesai & Dikembalikan',
            'ditolak' => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
