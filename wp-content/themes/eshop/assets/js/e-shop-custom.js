document.addEventListener('DOMContentLoaded', () => {
    if (typeof Swiper === 'undefined') return;

    document.querySelectorAll('.--hero-slider').forEach(slider => {
        new Swiper(slider, {
            slidesPerView: 1,
            loop: false,

            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },

            speed: 1500, // скорость анимации (мс)

            autoplay: {
                delay: 5000,          // 5 секунд
                disableOnInteraction: false, // не останавливать после свайпа
                pauseOnMouseEnter: true      // пауза при наведении
            },

            pagination: {
                el: slider.querySelector('.swiper-pagination'),
                clickable: true,
            },

            navigation: {
                nextEl: slider.querySelector('.swiper-button-next'),
                prevEl: slider.querySelector('.swiper-button-prev'),

            }
        });
    });

    document.querySelectorAll('.--banners-slider').forEach(slider => {
        new Swiper(slider, {
            slidesPerView: 1,
            loop: false,

            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },

            speed: 1500, // скорость анимации (мс)

            autoplay: {
                delay: 5000,          // 5 секунд
                disableOnInteraction: false, // не останавливать после свайпа
                pauseOnMouseEnter: true      // пауза при наведении
            },

            pagination: {
                el: slider.querySelector('.swiper-pagination'),
                clickable: true,
            },
        });
    });

    // === Универсальный плавный скролл ===
    function easeInOutQuad(t) { return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; }

    function smoothScrollToElement(selector, duration = 700) {
        const target = document.querySelector(selector);
        if (!target) return;
        document.documentElement.style.scrollBehavior = "auto";
        const element = document.scrollingElement || document.documentElement;
        const start = element.scrollTop;
        const targetTop = target.getBoundingClientRect().top + start - 160;
        const change = targetTop - start;
        const startTime = performance.now();

        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            element.scrollTop = start + change * easeInOutQuad(progress);
            if (elapsed < duration) requestAnimationFrame(animate);
            else document.documentElement.style.scrollBehavior = "";
        }
        requestAnimationFrame(animate);
    }

    function smoothScrollToTop(duration = 700) {
        const element = document.scrollingElement || document.documentElement;
        const start = element.scrollTop;
        const change = -start;
        const startTime = performance.now();

        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            element.scrollTop = start + change * easeInOutQuad(progress);
            if (elapsed < duration) requestAnimationFrame(animate);
        }
        requestAnimationFrame(animate);
    }

    // === Кнопка "вверх" ===
    const upArrow = document.querySelector(".arrow-up");
    if (upArrow) {
        upArrow.addEventListener("click", e => { e.preventDefault(); smoothScrollToTop(800); });
        window.addEventListener("scroll", () => {
            upArrow.classList.toggle("show", window.scrollY > 300);
        });
    }

});