<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'identifier',
        'class_or_dept',
        'phone',
        'category_scope',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function managedCategories()
    {
        return $this->belongsToMany(Category::class, 'category_admin');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function unreadMessagesCount(): int
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }

    public function isPeminjam(): bool
    {
        return in_array($this->role, ['siswa', 'guru']);
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canManageCategory(string $categorySlug): bool
    {
        if ($this->role !== 'admin') {
            return false;
        }

        if ($this->category_scope === 'all') {
            return true;
        }

        if ($this->category_scope === $categorySlug) {
            return true;
        }

        return $this->managedCategories()->where('slug', $categorySlug)->exists();
    }
}
