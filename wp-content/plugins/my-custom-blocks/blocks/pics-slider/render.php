<?php
defined('ABSPATH') || exit;

$items = $attributes['items'] ?? [];

if (!$items) {
    return '';
}
?>

<div class="swiper pics-slider">
    <div class="swiper-wrapper">


        <?php foreach ($items as $item) : ?>

            <div class="swiper-slide pics-slide">

                <?php if (!empty($item['img'])) : ?>
                    <a href="<?php echo esc_url($item['url'] ?? '#'); ?>">
                    </a>
                    <img src="<?php echo esc_url($item['img']); ?>" alt="<?php echo esc_attr($item['alt'] ?? ''); ?>">
                <?php endif; ?>

                <?php if (!empty($item['title'])) : ?>
                    <h3><?php echo esc_html($item['title']); ?></h3>
                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>
    <div class="swiper-pagination"></div>
</div>