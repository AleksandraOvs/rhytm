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
});