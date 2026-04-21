<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;

    // Melindungi ID, sisanya boleh diisi massal
    protected $guarded = ['id'];
    protected $fillable = [
        'mal_id',
        'title',
        'alternative_titles',
        'slug',
        'synopsis',
        'author',
        'type',
        'release_year',
        'finish_year',
        'cover_image',
        'status',
        'total_chapter',
        'total_volume',
        'mal_score',
        'mal_favorites',
    ];

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
            ->withPivot('reading_status', 'last_read_chapter', 'score')
            ->withTimestamps();
    }
    //fitur Manual Like
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'comic_likes')->withTimestamps();
    }

    // --- ACCESSOR UNTUK RATING GABUNGAN ---
    public function getCombinedScoreAttribute()
    {
        $localScore = $this->avg_score ?? 0;
        $malScore = $this->mal_score ?? 0;

        // Jika user lokal sudah merating DAN MAL punya rating = Diambil nilai tengahnya (Rata-rata)
        if ($localScore > 0 && $malScore > 0) {
            return ($localScore + $malScore) / 2;
        }

        // Jika tidak, tampilkan mana yang ada nilainya (Prioritaskan Lokal jika ada)
        return $localScore > 0 ? $localScore : $malScore;
    }

    // --- ACCESSOR UNTUK TOTAL LIKES ---
    public function getTotalLikesAttribute()
    {
        // Total dari database Jikan ditambah total user lokal yang mengklik Like
        $localLikes = $this->liked_by_users_count ?? $this->likedByUsers()->count();
        $malFavorites = $this->mal_favorites ?? 0;

        return $localLikes + $malFavorites;
    }
}
