<?php
defined('ABSPATH') || exit;

$items = $attributes['items'] ?? [];
if (!$items) return '';
?>

<div class="gallery-slider swiper">
    <div class="swiper-wrapper">

        <?php
        $chunks = array_chunk($items, 4); // делим по 4 картинки на слайд
        foreach ($chunks as $group) :
        ?>
            <div class="swiper-slide gallery-slider__slide">
                <?php foreach ($group as $item) : ?>
                    <?php if (!empty($item['url'])) : ?>
                        <a href="<?php echo esc_url($item['link'] ?: $item['url']); ?>" data-fancybox="gallery-slider" data-caption="<?php echo esc_attr($item['title'] ?? ''); ?>">
                            <img src="<?php echo esc_url($item['url']); ?>" alt="<?php echo esc_attr($item['alt'] ?? ''); ?>">
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

    </div>
    <div class="swiper-pagination"></div>
</div>