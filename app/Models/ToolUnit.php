<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_id',
        'unit_code',
        'status', // tersedia, dipinjam, rusak, hilang
        'condition', // baik, rusak ringan, rusak berat
        'notes',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
