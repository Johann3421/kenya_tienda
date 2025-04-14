
document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.novedades-carousel-track');
    const items = document.querySelectorAll('.novedades-carousel-item');
    const prevBtn = document.querySelector('.novedades-carousel-prev');
    const nextBtn = document.querySelector('.novedades-carousel-next');
    const dotsContainer = document.querySelector('.novedades-carousel-dots');

    let currentIndex = 0;
    let visibleItems = 4; // Valor por defecto para desktop
    let totalSlides = items.length;

    // Calcular items visibles según el ancho de pantalla
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

    // Crear indicadores
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

    // Actualizar posición del track
    function updateTrackPosition() {
        const itemWidth = items[0].offsetWidth;
        const gap = 20;
        const newPosition = -(currentIndex * (itemWidth + gap));

        track.style.transform = `translateX(${newPosition}px)`;

        // Actualizar dots activos
        document.querySelectorAll('.novedades-carousel-dot').forEach((dot, i) => {
            const dotPosition = i * visibleItems;
            dot.classList.toggle('active', currentIndex >= dotPosition && currentIndex <
                dotPosition + visibleItems);
        });
    }

    // Navegación
    function nextSlide() {
        if (currentIndex < totalSlides - visibleItems) {
            currentIndex++;
        } else {
            currentIndex = 0; // Volver al inicio
        }
        updateTrackPosition();
    }

    function prevSlide() {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = totalSlides - visibleItems; // Ir al final
        }
        updateTrackPosition();
    }

    // Ir a slide específico
    function goToSlide(index) {
        currentIndex = Math.min(Math.max(index, 0), totalSlides - visibleItems);
        updateTrackPosition();
    }

    // Event listeners
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    // Auto-desplazamiento
    let slideInterval;

    function startAutoSlide() {
        slideInterval = setInterval(() => {
            nextSlide();
        }, 5000);
    }

    function stopAutoSlide() {
        clearInterval(slideInterval);
    }

    // Inicializar
    function initCarousel() {
        updateVisibleItems();
        startAutoSlide();

        // Pausar al interactuar
        track.addEventListener('mouseenter', stopAutoSlide);
        track.addEventListener('mouseleave', startAutoSlide);

        // Touch events para móviles
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

    // Redimensionamiento
    window.addEventListener('resize', () => {
        updateVisibleItems();
    });

    // Iniciar carrusel
    initCarousel();
});

