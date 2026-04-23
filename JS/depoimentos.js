(function() {
    'use strict';

    // Aguarda o DOM carregar
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTestimonialsCarousel);
    } else {
        initTestimonialsCarousel();
    }

    function initTestimonialsCarousel() {
        let currentIndexDepo = 0;
        const containerDepo = document.getElementById('carouselContainer');

        // Verifica se existe o carrossel de depoimentos
        if (!containerDepo || !containerDepo.classList.contains('carousel-container')) {
            return;
        }

        const cardsDepo = containerDepo.querySelectorAll('.testimonial-card');
        const dotsContainerDepo = document.getElementById('carouselDots');
        
        if (cardsDepo.length === 0) {
            return;
        }

        let cardsPerViewDepo = 3;

        function updateCardsPerViewDepo() {
            const width = window.innerWidth;
            if (width <= 768) {
                cardsPerViewDepo = 1;
            } else if (width <= 1024) {
                cardsPerViewDepo = 2;
            } else {
                cardsPerViewDepo = 3;
            }
        }

        function getTotalSlidesDepo() {
            return Math.ceil(cardsDepo.length / cardsPerViewDepo);
        }

        function createDotsDepo() {
            if (!dotsContainerDepo) return;

            dotsContainerDepo.innerHTML = '';
            const total = getTotalSlidesDepo();

            for (let i = 0; i < total; i++) {
                const dotEl = document.createElement('div');
                dotEl.className = 'dot';
                if (i === 0) dotEl.classList.add('active');
                dotEl.addEventListener('click', () => goToSlideDepo(i));
                dotsContainerDepo.appendChild(dotEl);
            }
        }

        function updateDotsDepo() {
            if (!dotsContainerDepo) return;

            const dotEls = dotsContainerDepo.querySelectorAll('.dot');
            dotEls.forEach((dotEl, idx) => {
                dotEl.classList.toggle('active', idx === currentIndexDepo);
            });
        }

        function moveCarouselDepo(dir) {
            currentIndexDepo += dir;
            const total = getTotalSlidesDepo();

            if (currentIndexDepo < 0) {
                currentIndexDepo = total - 1;
            } else if (currentIndexDepo >= total) {
                currentIndexDepo = 0;
            }

            updateCarouselDepo();
        }

        function goToSlideDepo(idx) {
            currentIndexDepo = idx;
            updateCarouselDepo();
        }

        function updateCarouselDepo() {
            if (cardsDepo.length === 0) return;

            const cardW = cardsDepo[0].offsetWidth;
            const gapVal = 30;
            const offsetVal = -(currentIndexDepo * cardsPerViewDepo * (cardW + gapVal));
            
            // CORREÇÃO: Estava faltando o acento grave (template literal)
            containerDepo.style.transform = `translateX(${offsetVal}px)`;
            
            updateDotsDepo();
        }

        // Torna a função global para os botões HTML
        window.moveCarousel = moveCarouselDepo;

        // Inicializa
        updateCardsPerViewDepo();
        createDotsDepo();
        updateCarouselDepo();

        // Listener de resize otimizado
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                updateCardsPerViewDepo();
                createDotsDepo();
                updateCarouselDepo();
            }, 250);
        });
    }
})();