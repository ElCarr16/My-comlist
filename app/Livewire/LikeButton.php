<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comic;

class LikeButton extends Component
{
    public Comic $comic;
    public $isLiked = false;
    public $likesCount = 0;

    public function mount(Comic $comic)
    {
        $this->comic = $comic;

        if (auth()->check()) {
            // Diperbaiki: Gunakan Query langsung agar tidak memenuhi RAM memori server
            $this->isLiked = auth()->user()->likedComics()->where('comics.id', $comic->id)->exists();
        }

        $this->likesCount = $comic->likedByUsers()->count();
    }

    public function toggleLike()
    {
        if (!auth()->check()) return redirect()->route('login');

        $user = auth()->user();
        if ($this->isLiked) {
            $user->likedComics()->detach($this->comic->id);
            $this->isLiked = false;
            $this->likesCount--;
        } else {
            $user->likedComics()->attach($this->comic->id);
            $this->isLiked = true;
            $this->likesCount++;
        }

        // Dispatch event agar parent tahu ada perubahan (opsional)
        $this->dispatch('likeUpdated');
    }
    public function render()
    {
        return view('livewire.like-button');
    }
}
