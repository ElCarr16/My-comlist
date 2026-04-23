<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_name',
        'name',
        'email',
        'password',
        'role',
        'profile_image',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->user_name)) {
                $user->user_name = $user->name;
            }
        });
    }

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

    /**
     * RELASI INTERNAL: Menghubungkan user dengan komik yang ada di database kamu.
     * Digunakan untuk "My List" dan "Aktivitas Terakhir".
     */
    public function trackedComics()
    {
        return $this->belongsToMany(Comic::class, 'comic_user')
            ->withPivot('reading_status', 'score', 'last_read_chapter')
            ->withTimestamps();
    }

    /**
     * RELASI EKSTERNAL: Menghubungkan user dengan URL komik dari luar (Bajakan/Legal).
     * Digunakan untuk "Website Terintegrasi".
     */
    public function trackedSources()
    {
        return $this->hasMany(TrackedComic::class, 'user_id');
    }

    /**
     * RELASI LIKE: Untuk fitur menyukai komik.
     */
    public function likedComics()
    {
        return $this->belongsToMany(Comic::class, 'comic_likes')->withTimestamps();
    }

    /**
     * fungsi ini akan mengarahkan ke trackedComics() agar tidak error.
     */
    public function comics()
    {
        return $this->trackedComics();
    }
}