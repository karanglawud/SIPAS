<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'badge',
        'pj_name',
        'pj_role_title',
        'description',
        'icon',
        'color',
    ];

    public function tools()
    {
        return $this->hasMany(Tool::class);
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'category_admin');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
