<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_return_date' => 'date',
        'approved_at' => 'datetime',
        'assigned_units' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'bg-amber-100 text-amber-800 border-amber-300',
            'disetujui' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'dipinjam' => 'bg-blue-100 text-blue-800 border-blue-300',
            'selesai' => 'bg-slate-100 text-slate-800 border-slate-300',
            'ditolak' => 'bg-rose-100 text-rose-800 border-rose-300',
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
            default => ucfirst($this->status),
        };
    }
}
