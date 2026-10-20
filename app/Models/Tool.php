<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'spec',
        'location',
        'total_stock',
        'available_stock',
        'fine_per_day',
        'max_days',
        'image_gradient',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function units()
    {
        return $this->hasMany(ToolUnit::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function availableUnits()
    {
        return $this->units()->where('status', 'tersedia');
    }
}
