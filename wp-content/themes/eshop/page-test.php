<?php

/**
 * Template name: test
 */

get_header() ?>

<a href="<?php echo get_stylesheet_directory_uri() ?>/img/demo/slider1.jpg" data-fancybox="gallery">
    <img src="<?php echo get_stylesheet_directory_uri() ?>/img/demo/slider1.jpg">
</a>

<div
    class="swiper js-swiper"
    data-slides="1.4"
    data-space="30"
    data-loop="true"
    data-speed="800"
    data-breakpoints='{
         "768": {"slidesPerView": 2.2, "spaceBetween": 20},
         "1024": {"slidesPerView": 3, "spaceBetween": 30},
         "1200": {"slidesPerView": 4, "spaceBetween": 40}
     }'>

    <div class="swiper-wrapper">

        <div class="swiper-slide">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/img/demo/slider1.jpg" alt="">
        </div>

        <div class="swiper-slide">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/img/demo/slider2.jpg" alt="">
        </div>

        <div class="swiper-slide">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/img/demo/slider1.jpg" alt="">
        </div>

        <div class="swiper-slide">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/img/demo/slider2.jpg" alt="">
        </div>

    </div>

    <!-- Пагинация -->
    <div class="swiper-pagination"></div>

    <!-- Кнопки -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

</div>


<?php get_footer() ?>