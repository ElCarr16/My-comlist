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
            $this->isLiked = auth()->user()->likedComics->contains($comic->id);
        }

        // Panggil accessor yang kita buat di Model
        $this->likesCount = $comic->total_likes;
    }

    public function toggleLike()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($this->isLiked) {
            $user->likedComics()->detach($this->comic->id);
            $this->isLiked = false;
            $this->likesCount--; // Kurangi langsung di UI
        } else {
            $user->likedComics()->attach($this->comic->id);
            $this->isLiked = true;
            $this->likesCount++; // Tambah langsung di UI
        }
    }
    public function render()
    {
        return view('livewire.like-button');
    }
}
