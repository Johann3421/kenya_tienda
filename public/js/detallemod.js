
document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.novedades-carousel-track');
    const items = document.querySelectorAll('.novedades-carousel-item');
    const prevBtn = document.querySelector('.novedades-carousel-prev');
    const nextBtn = document.querySelector('.novedades-carousel-next');
    const dotsContainer = document.querySelector('.novedades-carousel-dots');

    if (!track || !prevBtn || !nextBtn || !dotsContainer || items.length === 0) {
        return;
    }

    let currentIndex = 0;
    let visibleItems = 4;
    let totalSlides = items.length;

    function updateVisibleItems() {
        if (window.innerWidth <= 576) {
            visibleItems = 1;
        } else if (window.innerWidth <= 992) {
            visibleItems = 2;
        } else {
            visibleItems = 4;
        }
        updateTrackPosition();
        createDots();
    }

    function createDots() {
        dotsContainer.innerHTML = '';
        const dotCount = Math.ceil(totalSlides / visibleItems);

        for (let i = 0; i < dotCount; i++) {
            const dot = document.createElement('div');
            dot.classList.add('novedades-carousel-dot');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(i * visibleItems));
            dotsContainer.appendChild(dot);
        }
    }

    function updateTrackPosition() {
        if (!items[0]) return;
        const itemWidth = items[0].offsetWidth;
        const gap = 20;
        const newPosition = -(currentIndex * (itemWidth + gap));

        track.style.transform = `translateX(${newPosition}px)`;

        document.querySelectorAll('.novedades-carousel-dot').forEach((dot, i) => {
            const dotPosition = i * visibleItems;
            dot.classList.toggle('active', currentIndex >= dotPosition && currentIndex <
                dotPosition + visibleItems);
        });
    }

    function nextSlide() {
        if (currentIndex < totalSlides - visibleItems) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        updateTrackPosition();
    }

    function prevSlide() {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = totalSlides - visibleItems;
        }
        updateTrackPosition();
    }

    function goToSlide(index) {
        currentIndex = Math.min(Math.max(index, 0), totalSlides - visibleItems);
        updateTrackPosition();
    }

    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    let slideInterval;

    function startAutoSlide() {
        slideInterval = setInterval(() => {
            nextSlide();
        }, 5000);
    }

    function stopAutoSlide() {
        clearInterval(slideInterval);
    }

    function initCarousel() {
        updateVisibleItems();
        startAutoSlide();

        track.addEventListener('mouseenter', stopAutoSlide);
        track.addEventListener('mouseleave', startAutoSlide);

        let touchStartX = 0;
        let touchEndX = 0;

        track.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoSlide();
        }, {
            passive: true
        });

        track.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
            startAutoSlide();
        }, {
            passive: true
        });

        function handleSwipe() {
            const diff = touchStartX - touchEndX;
            if (diff > 50) nextSlide();
            if (diff < -50) prevSlide();
        }
    }

    window.addEventListener('resize', () => {
        updateVisibleItems();
    });

    initCarousel();
});
