<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ComicUser extends Pivot
{
    protected $table = 'comic_user';

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'last_read_chapter' => 'integer',
        ];
    }
}
