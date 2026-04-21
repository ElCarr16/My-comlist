<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comic;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

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

    public ?Comic $selected_comic = null;
    public $last_read_chapter = 0;
    public $status = 'reading';
    public $score = 10;

    protected $listeners = ['likeUpdated' => '$refresh'];

    public function applyFilters()
    {
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

    public function render()
    {
        // 1. Inisialisasi Query dengan Aggregates
        $query = Comic::query()
            ->withCount('likedByUsers')
            ->withAvg('users as avg_score', 'comic_user.score');

        // 2. Filter Pencarian
        if (!empty($this->search)) {
            $searchTerm = '%' . strtolower($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(alternative_titles) LIKE ?', [$searchTerm]);
            });
        }

        // 3. Filter Genre
        if (!empty($this->selectedGenres)) {
            $query->whereHas('genres', function ($q) {
                $q->whereIn('genres.id', $this->selectedGenres);
            });
        }

        // 4. Filter Status
        if (!empty($this->filterStatus)) {
            $query->whereRaw('TRIM(status) = ?', [$this->filterStatus]);
        }

        // 5. Filter Tipe
        if (!empty($this->type)) {
            $query->whereRaw('LOWER(type) = ?', [strtolower($this->type)]);
        }

        // 6. Filter Tahun
        if ($this->year) {
            $query->where('release_year', $this->year);
        }

        // 7. Sorting Gabungan (MAL + Lokal)
        $direction = $this->sortOrder;

        switch ($this->sort) {
            case 'popular':
                // (MAL Favorites + Like Lokal)
                $query->orderByRaw('(COALESCE(mal_favorites, 0) + liked_by_users_count) ' . $direction);
                break;
            case 'rating':
                // (MAL Score + Rata-rata Lokal) / 2
                $query->orderByRaw('(COALESCE(mal_score, 0) + COALESCE(avg_score, 0)) / 
                                   (CASE WHEN mal_score > 0 AND avg_score > 0 THEN 2 ELSE 1 END) ' . $direction);
                break;
            case 'name':
                $query->orderBy('title', $direction);
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
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
