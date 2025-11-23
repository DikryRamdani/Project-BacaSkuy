// Hero Banner Carousel Component
document.addEventListener("DOMContentLoaded", function () {
    const carousel = document.getElementById("hero-carousel");
    if (!carousel) return;

    const totalSlides = parseInt(carousel.dataset.totalSlides);
    if (totalSlides <= 1) return; // No need for carousel if only 1 slide

    const slides = carousel.querySelectorAll(".carousel-slide");
    const dots = carousel.querySelectorAll(".carousel-dot");
    const prevBtn = document.getElementById("carousel-prev");
    const nextBtn = document.getElementById("carousel-next");

    let currentSlide = 0;
    let autoPlayInterval;

    function goToSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach((slide) => slide.classList.remove("active"));
        dots.forEach((dot) => {
            dot.classList.remove("bg-white", "w-8");
            dot.classList.add("bg-white/50", "w-2");
        });

        // Add active class to current slide and dot
        currentSlide = index;
        slides[currentSlide].classList.add("active");
        dots[currentSlide].classList.remove("bg-white/50", "w-2");
        dots[currentSlide].classList.add("bg-white", "w-8");

        // Reset auto-play timer
        resetAutoPlay();
    }

    function nextSlide() {
        const next = (currentSlide + 1) % totalSlides;
        goToSlide(next);
    }

    function prevSlide() {
        const prev = (currentSlide - 1 + totalSlides) % totalSlides;
        goToSlide(prev);
    }

    function startAutoPlay() {
        autoPlayInterval = setInterval(nextSlide, 7000); // 7 seconds
    }

    function stopAutoPlay() {
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
        }
    }

    function resetAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }

    // Event listeners for navigation buttons
    if (nextBtn) nextBtn.addEventListener("click", nextSlide);
    if (prevBtn) prevBtn.addEventListener("click", prevSlide);

    // Event listeners for dots
    dots.forEach((dot, index) => {
        dot.addEventListener("click", () => goToSlide(index));
    });

    // Pause auto-play on hover
    carousel.addEventListener("mouseenter", stopAutoPlay);
    carousel.addEventListener("mouseleave", startAutoPlay);

    // Start auto-play
    startAutoPlay();
});
