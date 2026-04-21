<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comic;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ComicSearch extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $selectedGenres = [];
    public $filterStatus = '';
    public $type = '';
    public $year = '';
    public $sort = 'latest';
    public $sortOrder = 'desc';

    // Diperbaiki: Menambahkan ?Comic agar tidak error diubah ke array oleh Livewire 3
    public ?Comic $selected_comic = null;
    public $last_read_chapter = 0;
    public $status = 'reading';
    public $score = 10;

    // Listener agar Paginasi/UI Search langsung memuat ulang data saat Like ditekan
    protected $listeners = ['likeUpdated' => '$refresh'];

    public function applyFilters()
    {
        // Fungsi ini dipanggil oleh tombol Hijau (Terapkan)
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'selectedGenres', 'filterStatus', 'type', 'year', 'sort', 'sortOrder']);
        $this->resetPage();
    }

    public function toggleDirection()
    {
        $this->sortOrder = $this->sortOrder === 'desc' ? 'asc' : 'desc';
        $this->resetPage();
    }

    public function openProgressModal($comicId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->selected_comic = Comic::find($comicId);
        $userProgress = Auth::user()->trackedComics()->where('comics.id', $comicId)->first();

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
        if (!Auth::check()) return redirect()->route('login');

        $rules = [
            'last_read_chapter' => 'required|integer|min:0',
            'status' => 'required',
            'score' => 'nullable|integer|min:1|max:10',
        ];

        if ($this->selected_comic && $this->selected_comic->total_chapter > 0) {
            $rules['last_read_chapter'] .= '|max:' . $this->selected_comic->total_chapter;
        }

        $this->validate($rules);

        Auth::user()->trackedComics()->syncWithoutDetaching([
            $this->selected_comic->id => [
                'reading_status' => $this->status,
                'last_read_chapter' => $this->last_read_chapter,
                'score' => $this->score,
                'updated_at' => now(),
            ]
        ]);

        $this->dispatch('closeModal');
        session()->flash('message', 'Progress berhasil diperbarui!');
    }

    public function render()
    {
        $query = Comic::query()
            ->withCount('likedByUsers as liked_by_users_count')
            ->withAvg('users as avg_score', 'comic_user.score');

        // Filter Pencarian
        if (!empty($this->search)) {
            $searchTerm = '%' . strtolower($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(alternative_titles) LIKE ?', [$searchTerm]);
            });
        }

        // PERBAIKAN LOGIKA GENRE: Menjadi OR (Pilih Action/Romance, munculkan yang punya salah satu)
        if (!empty($this->selectedGenres)) {
            $query->whereHas('genres', function ($q) {
                $q->whereIn('genres.id', $this->selectedGenres);
            }); // <-- Syarat count() dihapus agar tidak terlalu ketat
        }

        if (!empty($this->filterStatus)) {
            $query->whereRaw('TRIM(status) = ?', [$this->filterStatus]);
        }

        if (!empty($this->type)) {
            $query->whereRaw('LOWER(type) = ?', [strtolower($this->type)]);
        }

        if ($this->year) {
            $query->where('release_year', $this->year);
        }

        $direction = $this->sortOrder;
        switch ($this->sort) {
            case 'popular':
                $query->orderByRaw("((SELECT COUNT(*) FROM users INNER JOIN comic_likes ON users.id = comic_likes.user_id WHERE comics.id = comic_likes.comic_id) + COALESCE(mal_favorites, 0)) {$direction}")->orderBy('id', $direction);
                break;
            case 'rating':
                $query->orderByRaw("(COALESCE((SELECT AVG(score) FROM comic_user WHERE comic_id = comics.id), 0) + COALESCE(mal_score, 0)) {$direction}")->orderBy('mal_id', $direction);
                break;
            case 'name':
                $query->orderBy('title', $direction)->orderBy('id', $direction);
                break;
            default:
                $query->orderBy('release_year', $direction)->orderBy('id', $direction);
                break;
        }

        return view('livewire.comic-search', [
            'comics' => $query->paginate(24),
            'genres' => Genre::orderBy('name', 'asc')->get(),
            'statusOptions' => Comic::distinct()
                ->whereNotNull('status')
                ->where('status', '!=', '')
                ->pluck('status')
                ->map(fn($item) => trim($item))
                ->unique()
                ->sort()
        ]);
    }
}
