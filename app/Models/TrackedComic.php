<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackedComic extends Model
{
    protected $table = 'tracked_comics';

    // WAJIB tambahkan 'user_id' di sini agar bisa diisi oleh controller
    protected $fillable = ['user_id', 'title', 'url', 'last_chapter_title'];
}