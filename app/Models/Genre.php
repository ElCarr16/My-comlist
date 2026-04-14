<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi balikan Many-to-Many ke Comic
     */
    public function comics()
    {
        return $this->belongsToMany(Comic::class, 'comic_genre');
    }
}