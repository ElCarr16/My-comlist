namespace App\Livewire;

use Livewire\Component;
use App\Models\Comic;

class LikeButton extends Component
{
    public $comic; // Kita butuh variabel komik
    public $isLiked;
    public $likesCount;

    // Mount dijalankan sekali saat komponen pertama kali dimuat
    public function mount(Comic $comic)
    {
        $this->comic = $comic;
        $this->isLiked = auth()->user()->likedComics->contains($comic->id);
        $this->likesCount = $comic->likedByUsers()->count();
    }

    public function toggleLike()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->likedComics->contains($this->comic->id)) {
            $user->likedComics()->detach($this->comic->id);
            $this->isLiked = false;
        } else {
            $user->likedComics()->attach($this->comic->id);
            $this->isLiked = true;
        }

        // Update count otomatis
        $this->likesCount = $this->comic->likedByUsers()->count();
    }

    public function render()
    {
        return view('livewire.like-button');
    }
}
