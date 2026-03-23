<?php

/**
 * WooCommerce main template
 *
 * Используется для:
 * – shop
 * – product category
 * – product tag
 * – single product
 * – cart / checkout / account
 */

defined('ABSPATH') || exit;

get_header('shop');
?>

<main id="primary" class="site-main woocommerce-page">

    <?php get_template_part('template-parts/page-header'); ?>

    <div class="woocommerce-page__content">
        <div class="container">
            <?php if (woocommerce_content()) : ?>
                <?php woocommerce_content(); ?>
            <?php endif; ?>

            <?php
            /**
             * Закрывающие теги + сайдбар (если есть)
             */
            do_action('woocommerce_after_main_content');
            ?>

        </div>
    </div>
</main>

<?php
get_footer('shop');
