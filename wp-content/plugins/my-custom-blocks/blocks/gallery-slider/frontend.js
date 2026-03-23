document.addEventListener('DOMContentLoaded', function () {
    const sliders = document.querySelectorAll('.gallery-slider');

    sliders.forEach(slider => {
        new Swiper(slider, {
            slidesPerView: 1.1, // 1 слайд = 4 картинки
            spaceBetween: 7,
            loop: false,
            pagination: {
                el: slider.querySelector('.swiper-pagination'),
                clickable: true
            },
            breakpoints: {
                576: {
                    slidesPerView: 1.3,
                    spaceBetween: 16,
                }
            }
        });
    });
});