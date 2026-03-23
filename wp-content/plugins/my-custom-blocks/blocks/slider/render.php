<?php
defined('ABSPATH') || exit;

$items = $attributes['items'] ?? [];

if (!$items) {
    return '';
}
?>

<div class="swiper slider">
    <div class="swiper-wrapper">


        <?php foreach ($items as $item) : ?>

            <div class="swiper-slide slider-slide">

                <?php if (!empty($item['img'])) : ?>
                    <a href="<?php echo esc_url($item['img']); ?>" data-fancybox="gallery-slider">
                        <img src="<?php echo esc_url($item['img']); ?>" alt="<?php echo esc_attr($item['alt'] ?? ''); ?>">
                    </a>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>
    <div class="swiper-pagination"></div>
</div>