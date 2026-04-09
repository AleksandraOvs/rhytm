<?php
/*
Plugin Name: Custom WooCommerce Filters
Description: AJAX фильтр товаров WooCommerce через шорткод [shop_filters]
Version: 2.0
Author: PurpleWeb
*/

if (!defined('ABSPATH')) exit;
/* ---------------------------------------------------
 * Подключение JS и CSS
 * --------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_style('jquery-ui-style', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');

    wp_enqueue_style('cwc-style', plugin_dir_url(__FILE__) . 'css/style.css');

    wp_enqueue_script(
        'cwc-ajax-filters',
        plugin_dir_url(__FILE__) . 'js/admin-ajax.js',
        ['jquery', 'jquery-ui-slider'],
        '2.0',
        true
    );

    wp_localize_script('cwc-ajax-filters', 'cwc_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});
/* ---------------------------------------------------
 * META QUERY ДЛЯ ЦЕНЫ (универсальный)
 * --------------------------------------------------- */
function cwc_price_meta_query($min, $max)
{
    return [
        'relation' => 'OR',
        [
            'key'     => '_price',
            'value'   => [$min, $max],
            'compare' => 'BETWEEN',
            'type'    => 'NUMERIC',
        ],
        [
            'key'     => '_min_variation_price',
            'value'   => $max,
            'compare' => '<=',
            'type'    => 'NUMERIC',
        ],
        [
            'key'     => '_max_variation_price',
            'value'   => $min,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ],
    ];
}

/* ---------------------------------------------------
 * Фильтр атрибутов
 * --------------------------------------------------- */
function cwc_render_attribute_filter($taxonomy, $title, $current_cat_id = 0)
{
    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false
    ]);

    if (empty($terms) || is_wp_error($terms)) return '';

    // list($min_price, $max_price) = cwc_get_store_price_range();

    ob_start();
?>

    <div class="single-sidebar-wrap">
        <h3 class="sidebar-title"><?php echo esc_html($title); ?></h3>

        <div class="sidebar-body">
            <ul class="sidebar-list" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">

                <?php foreach ($terms as $term):

                    $tax_query = [
                        'relation' => 'AND',
                        [
                            'taxonomy' => $taxonomy,
                            'field'    => 'slug',
                            'terms'    => $term->slug,
                        ]
                    ];

                    if ($current_cat_id) {
                        $tax_query[] = [
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $current_cat_id,
                        ];
                    }

                    $count_query = new WP_Query([
                        'post_type'      => 'product',
                        'posts_per_page' => 1,
                        'tax_query'      => $tax_query,
                        // 'meta_query'     => [cwc_price_meta_query($min_price, $max_price)],
                    ]);

                    $count = $count_query->found_posts;
                ?>

                    <li>
                        <a href="#"
                            class="filter-item"
                            data-slug="<?php echo esc_attr($term->slug); ?>"
                            data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                            <span class="filter-checkbox"></span>
                            <?php echo esc_html($term->name); ?> <?php //echo $count; 
                                                                    ?>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>
        </div>
    </div>

<?php
    return ob_get_clean();
}

?>
<?php

/* ---------------------------------------------------
 * ШОРТКОД
 * --------------------------------------------------- */
function cwc_shop_filters_shortcode()
{
    $current_cat_id = is_product_category() ? get_queried_object_id() : 0;

    ob_start(); ?>

    <div class="sidebar-area-wrapper _filters"
        data-current-cat="<?php echo esc_attr($current_cat_id); ?>">

        <?php //echo cwc_render_price_filter(); 
        ?>

        <!-- В наличии -->
        <!-- <div class="single-sidebar-wrap">
            <ul data-taxonomy="instock_filter">
                <li>
                    <a href="#" class="filter-item" data-slug="instock">
                        <span class="filter-checkbox"></span>
                        Есть в наличии
                    </a>
                </li>
            </ul>
        </div> -->

        <?php
        echo cwc_render_attribute_filter('pa_czvet', 'Цвет', $current_cat_id);
        echo cwc_render_attribute_filter('pa_kolichestvo-sec', 'Количество секций', $current_cat_id);
        echo cwc_render_attribute_filter('pa_pl-obogreva', 'Площадь обогрева', $current_cat_id);
        echo cwc_render_attribute_filter('pa_podkluchenie', 'Подключение', $current_cat_id);
        echo cwc_render_attribute_filter('pa_teplootdacha', 'Теплоотдача', $current_cat_id);
        ?>

        <div class="cwc-filter-actions">
            <button id="cwc-apply-filters">Применить</button>
            <button id="cwc-reset-filters">Сбросить</button>
        </div>

    </div>

<?php
    return ob_get_clean();
}
add_shortcode('shop_filters', 'cwc_shop_filters_shortcode');

/* ---------------------------------------------------
 * AJAX
 * --------------------------------------------------- */
add_action('wp_ajax_cwc_filter_products', 'cwc_filter_products');
add_action('wp_ajax_nopriv_cwc_filter_products', 'cwc_filter_products');

function cwc_filter_products()
{
    // list($store_min, $store_max) = cwc_get_store_price_range();

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'tax_query'      => ['relation' => 'AND'],
        'meta_query'     => ['relation' => 'AND'],
    ];

    /* Категория */
    if (!empty($_POST['current_cat_id'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => (int) $_POST['current_cat_id'],
        ];
    }

    /* В наличии */
    // if (!empty($_POST['instock'])) {
    //     $args['meta_query'][] = [
    //         'key'   => '_stock_status',
    //         'value' => 'instock',
    //     ];
    // }

    /* Атрибуты */
    foreach ($_POST as $key => $value) {

        if (preg_match('/^filter_(pa_[a-z0-9\-]+)$/', $key, $m)) {

            $terms = is_array($value) ? $value : [$value];

            $args['tax_query'][] = [
                'taxonomy' => $m[1],
                'field'    => 'slug',
                'terms'    => array_map('wc_clean', $terms),
                'operator' => 'IN',
            ];
        }
    }

    /* Цена */
    // $min_price = isset($_POST['min_price']) ? (int) $_POST['min_price'] : $store_min;
    // $max_price = isset($_POST['max_price']) ? (int) $_POST['max_price'] : $store_max;

    // $args['meta_query'][] = cwc_price_meta_query($min_price, $max_price);

    /* Сортировка */
    $ordering = WC()->query->get_catalog_ordering_args($_POST['orderby'] ?? '');
    $args['orderby'] = $ordering['orderby'];
    $args['order']   = $ordering['order'];

    if (!empty($ordering['meta_key'])) {
        $args['meta_key'] = $ordering['meta_key'];
    }

    /* Запрос */
    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<p>Товары не найдены</p>';
    }

    wp_reset_postdata();

    wp_send_json_success([
        'html' => ob_get_clean()
    ]);
}
