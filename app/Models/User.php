<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Firefly\FilamentBlog\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser; // <-- DITAMBAHKAN
use Filament\Panel; // <-- DITAMBAHKAN

class User extends Authenticatable implements FilamentUser // <-- DITAMBAHKAN 'implements FilamentUser'
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // 👇 METHOD BARU DITAMBAHKAN DI SINI 👇
    /**
     * Menentukan apakah user bisa mengakses Filament Panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Izinkan akses hanya jika emailnya adalah 'admin@gmail.com'
        return $this->email === 'admin@gmail.com';

        // --- ATAU CARA YANG LEBIH KEREN PAKE ROLE (karena kamu pake Spatie) ---
        // return $this->hasRole('admin');
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes');
    }
    public function memberProfile()
    {
        return $this->hasOne(member::class);
    }

    public function transactions()
    {
        return $this->hasMany(transaction::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}