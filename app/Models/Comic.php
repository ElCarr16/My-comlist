<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;

    // Melindungi ID, sisanya boleh diisi massal
    protected $guarded = ['id'];

    /**
     * Relasi Many-to-Many ke Genre
     */
    public function genres()
    {
        // Parameter kedua adalah nama tabel pivotnya
        return $this->belongsToMany(Genre::class, 'comic_genre');
    }

    /**
     * Relasi Many-to-Many ke User (Fitur Tracker/MyList)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'comic_user')
                    ->withPivot('reading_status', 'score', 'last_read_chapter')
                    ->withTimestamps();
    }
}