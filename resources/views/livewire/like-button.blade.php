
<div>
    @auth
        <button wire:click="toggleLike"
            class="btn {{ $isLiked ? 'btn-orange' : 'btn-dark border border-secondary text-white' }} w-100 py-3 fw-bold shadow-sm"
            style="border-radius: 16px; transition: 0.3s;">
            <i class="bi {{ $isLiked ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
            {{ $likesCount }} Likes
        </button>
    @else
        <a href="{{ route('login') }}" class="btn btn-dark border border-secondary text-white w-100 py-3 fw-bold"
            style="border-radius: 16px;">
            <i class="bi bi-heart me-2"></i> Login untuk Like
        </a>
    @endauth
</div>
