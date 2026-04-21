<div>
    @auth
        <button wire:click="toggleLike" class="btn p-0 border-0 shadow-none d-flex align-items-center gap-1" style="background: transparent;">
            {{-- Hati merah jika dilike, abu-abu jika belum --}}
            <i class="bi {{ $isLiked ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }}" style="font-size: 1.1rem; transition: 0.2s;"></i>
            <span class="fw-semibold text-secondary" style="font-size: 0.9rem;">{{ $likesCount }}</span>
        </button>
    @else
        <a href="{{ route('login') }}" class="text-decoration-none d-flex align-items-center gap-1 text-secondary">
            <i class="bi bi-heart text-secondary" style="font-size: 1.1rem;"></i>
            <span class="fw-semibold" style="font-size: 0.9rem;">{{ $likesCount }}</span>
        </a>
    @endauth
</div>
