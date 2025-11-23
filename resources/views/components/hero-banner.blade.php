{{-- Hero Banner Component - Carousel untuk highlight manhwa --}}
<div class="relative rounded-lg overflow-hidden shadow-lg" id="hero-carousel" data-total-slides="{{ $totalSlides }}">
    {{-- Carousel Slides Container --}}
    <div class="carousel-slides">
        @foreach ($slides as $index => $slide)
            <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                {{-- Background Image dengan blur effect --}}
                <div class="absolute inset-0 z-0">
                    <img src="{{ $slide['coverImage'] }}" alt="{{ $slide['title'] }}"
                        class="w-full h-full object-cover scale-110" style="filter: blur(8px);">
                    {{-- Dark overlay untuk readability --}}
                    <div class="absolute inset-0 bg-linear-to-r from-black/80 via-black/60 to-black/80"></div>
                </div>


                <div class="relative flex items-center h-80 md:h-96 px-8 md:px-16 z-10">
                    {{-- Content Section --}}
                    <div class="flex-1 text-white">
                        <h1 class="text-3xl md:text-4xl font-bold mb-3"
                            style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8), -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;">
                            {{ $slide['title'] }}</h1>

                        {{-- Rating Badge --}}
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center bg-red-500 px-3 py-1 rounded-full">
                                <svg class="w-4 h-4 fill-current mr-1" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                                <span class="font-semibold text-sm">{{ $slide['rating'] }}</span>
                            </div>
                            <span
                                class="bg-red-500 px-3 py-1 rounded-full text-sm font-medium">{{ $slide['genre'] }}</span>
                        </div>

                        {{-- Description --}}
                        <p class="text-sm md:text-base leading-relaxed mb-6 max-w-2xl"
                            style="text-shadow: 1px 1px 3px rgba(0,0,0,0.7);">
                            {{ \Illuminate\Support\Str::limit($slide['description'], 150) }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Navigation Dots --}}
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-20">
        @for ($i = 0; $i < $totalSlides; $i++)
            <button
                class="carousel-dot h-2 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-white w-8' : 'bg-white/50 w-2' }}"
                data-slide="{{ $i }}"></button>
        @endfor
    </div>

    {{-- Navigation Arrows --}}
    <button id="carousel-prev"
        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-3 rounded-full transition-all duration-300 z-20">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    <button id="carousel-next"
        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-3 rounded-full transition-all duration-300 z-20">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
</div>

<style>
    #hero-carousel {
        min-height: 20rem;
        /* 320px */
    }

    @media (min-width: 768px) {
        #hero-carousel {
            min-height: 24rem;
            /* 384px */
        }
    }

    .carousel-slides {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: inherit;
    }

    .carousel-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        pointer-events: none;
    }

    .carousel-slide.active {
        opacity: 1;
        pointer-events: auto;
    }
</style>
@push('scripts')
    <script src="{{ asset('js/component/heroBanner.js') }}"></script>
@endpush
