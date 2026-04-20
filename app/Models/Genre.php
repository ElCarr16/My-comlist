<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Jangan lupa import ini!

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug']; // Pastikan slug ada di sini

    // Fungsi ini akan berjalan otomatis saat Genre dibuat
    protected static function booted()
    {
        static::creating(function ($genre) {
            if (empty($genre->slug)) {
                $genre->slug = Str::slug($genre->name);
            }
        });
    }

    public function comics()
    {
        return $this->belongsToMany(Comic::class, 'comic_genre');
    }
}
