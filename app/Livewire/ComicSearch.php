<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comic;
use App\Models\Genre;
use App\Models\ComicUser; // Tambahkan ini jika kamu punya model pivot
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ComicSearch extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Properti untuk Filter
    public $search = '';
    public $genre = '';
    public $year = '';
    public $sort = 'latest';
    public $sortOrder = 'desc';

    // Properti untuk Modal Update Progress
    public $selected_comic; // Untuk menyimpan data komik yang sedang diedit
    public $last_read_chapter = 0;
    public $status = 'reading';
    public $score = 10;

    // Reset halaman ke 1 setiap kali filter berubah
    public function updating($property)
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'genre', 'year', 'sort', 'sortOrder']);
    }

    public function toggleDirection()
    {
        $this->sortOrder = $this->sortOrder === 'desc' ? 'asc' : 'desc';
        $this->resetPage();
    }

    // FUNGSI BARU: Untuk memuat data komik ke dalam modal
    public function openProgressModal($comicId)
    {
        $this->selected_comic = Comic::find($comicId);

        // Ambil data progress user saat ini jika ada
        $userProgress = Auth::user()->comics()->where('comic_id', $comicId)->first();

        if ($userProgress) {
            $this->last_read_chapter = $userProgress->pivot->last_read_chapter;
            $this->status = $userProgress->pivot->reading_status;
            $this->score = $userProgress->pivot->score;
        } else {
            $this->reset(['last_read_chapter', 'status', 'score']);
        }
    }

    public function updateProgress()
    {
        // Pastikan user sudah login
        if (!Auth::check()) return redirect()->route('login');

        $rules = [
            'last_read_chapter' => 'required|integer|min:0',
            'status' => 'required',
            'score' => 'nullable|integer|min:1|max:10',
        ];

        // Validasi MAX chapter jika total_chapter diketahui (> 0)
        if ($this->selected_comic && $this->selected_comic->total_chapter > 0) {
            $rules['last_read_chapter'] .= '|max:' . $this->selected_comic->total_chapter;
        }

        $this->validate($rules);

        // Simpan ke tabel pivot comic_user
        Auth::user()->comics()->syncWithoutDetaching([
            $this->selected_comic->id => [
                'reading_status' => $this->status,
                'last_read_chapter' => $this->last_read_chapter,
                'score' => $this->score,
                'updated_at' => now(),
            ]
        ]);

        $this->dispatch('closeModal'); // Kirim sinyal untuk tutup modal di browser
        session()->flash('message', 'Progress berhasil diperbarui!');
    }

    public function render()
    {
        $query = Comic::query()
            ->withCount('likedByUsers')
            ->withAvg('users as avg_score', 'comic_user.score');

        // Filter Search
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Filter Genre
        if ($this->genre) {
            $query->whereHas('genres', function ($q) {
                $q->where('genres.id', $this->genre);
            });
        }

        // Filter Tahun
        if ($this->year) {
            $query->where('release_year', $this->year);
        }

        // Logika Sorting & Urutan
        $direction = $this->sortOrder;

        if ($this->sort == 'popular') {
            $query->orderBy('liked_by_users_count', $direction);
        } elseif ($this->sort == 'rating') {
            $query->orderBy('avg_score', $direction);
        } elseif ($this->sort == 'name') {
            $query->orderBy('title', $direction);
        } else {
            // Gunakan release_year karena itu kolom yang benar di tabel kamu
            $query->orderBy('release_year', $direction)->orderBy('created_at', $direction);
        }
        return view('livewire.comic-search', [
            'comics' => $query->paginate(12),
            'genres' => Genre::orderBy('name', 'asc')->get()
        ]);
    }
}
