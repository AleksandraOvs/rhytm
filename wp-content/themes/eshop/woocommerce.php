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

<?php
/**
 * Хуки WooCommerce для уведомлений, хлебных крошек и открытия контейнера
 */
do_action('woocommerce_before_main_content');
?>

<div class="woocommerce-page__content">
    <?php get_template_part('template-parts/page-header') ?>
    <div class="container">
        <?php
        // ----------------------------
        // Архив товаров с категориями и якорями
        // ----------------------------
        if (is_shop() || is_product_taxonomy()) {

            $parent_id = 0;

            if (is_product_taxonomy()) {
                $current = get_queried_object();
                $parent_id = $current->term_id;
            }

            // Получаем категории
            $categories = get_terms([
                'taxonomy'   => 'product_cat',
                'parent'     => $parent_id,
                'hide_empty' => true,
            ]);

            // ----------------------------
            // Сетка категорий (якоря)
            // ----------------------------
            if (!empty($categories) && !is_wp_error($categories)) {
                echo '<div class="categories-grid">';
                foreach ($categories as $cat) {
                    $anchor = 'cat-' . $cat->term_id;
        ?>
                    <a class="category-item__link" href="#<?php echo esc_attr($anchor); ?>">
                        <div class="category-title hover-effect"><?php echo esc_html($cat->name); ?></div>
                    </a>
        <?php
                }
                echo '</div>';
            }

            // ----------------------------
            // Товары по категориям
            // ----------------------------
            if (!empty($categories)) {

                foreach ($categories as $cat) {

                    $products = wc_get_products([
                        'status'   => 'publish',
                        'limit'    => -1,
                        'category' => [$cat->slug],
                    ]);

                    if (!empty($products)) {

                        $anchor = 'cat-' . $cat->term_id;

                        echo '<h2 id="' . esc_attr($anchor) . '" class="category-heading">' . esc_html($cat->name) . '</h2>';

                        // ВАЖНО: стандартный wrapper WooCommerce
                        woocommerce_product_loop_start();

                        global $product;

                        foreach ($products as $product_obj) {

                            $product = $product_obj; // 👉 подменяем глобальный продукт

                            // Если вдруг нужен post (редко, но бывает полезно)
                            if ($product->get_id()) {
                                $post_object = get_post($product->get_id());
                                setup_postdata($GLOBALS['post'] = &$post_object);
                            }

                            wc_get_template_part('content', 'product');
                        }

                        wp_reset_postdata();

                        woocommerce_product_loop_end();
                    }
                }
            }
        } elseif (is_product()) {
            // Одиночный товар
            wc_get_template('single-product.php');
        } else {
            // Для других страниц WooCommerce (cart, checkout, account)
            woocommerce_content();
            echo 'woocommerce content';
        }
        ?>
    </div>
</div>

<?php
/**
 * Хуки WooCommerce для закрытия контейнера и других действий
 */
do_action('woocommerce_after_main_content');

get_footer('shop');
