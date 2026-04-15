<?php
/*
Plugin Name: Custom WooCommerce Filters
Description: AJAX фильтр товаров WooCommerce через шорткод [shop_filters]
Version: 3.0
Author: PurpleWeb
*/

if (!defined('ABSPATH')) exit;

/* ---------------------------------------------------
 * ЛОГИРОВАНИЕ (WP DEBUG)
 * --------------------------------------------------- */
function cwc_log($label, $data = null)
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[CWC] ' . $label . ': ' . print_r($data, true));
    }
}

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
        '3.0',
        true
    );

    wp_localize_script('cwc-ajax-filters', 'cwc_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});

/* ---------------------------------------------------
 * Получить активные фильтры
 * --------------------------------------------------- */
function cwc_get_active_filters()
{
    $filters = [];

    foreach ($_POST as $key => $value) {
        if (preg_match('/^filter_(pa_[a-z0-9\-]+)$/', $key, $m)) {
            $filters[$m[1]] = (array) $value;
        }
    }

    // 🔥 LOG: какие фильтры распарсились
    cwc_log('PARSED ACTIVE FILTERS', $filters);

    return $filters;
}

/* ---------------------------------------------------
 * Динамический фильтр (главный)
 * --------------------------------------------------- */
function cwc_render_attribute_filter_dynamic($taxonomy, $title, $current_cat_id = 0, $active_filters = [])
{
    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false
    ]);

    if (empty($terms) || is_wp_error($terms)) return '';

    ob_start();
?>

    <div class="single-sidebar-wrap">
        <h3 class="sidebar-title"><?php echo esc_html($title); ?></h3>

        <div class="sidebar-body">
            <ul class="sidebar-list" data-taxonomy="<?php echo esc_attr($taxonomy); ?>">

                <?php foreach ($terms as $term):

                    $tax_query = ['relation' => 'AND'];

                    // категория
                    if ($current_cat_id) {
                        $tax_query[] = [
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $current_cat_id,
                        ];
                    }

                    // активные фильтры
                    foreach ($active_filters as $tax => $values) {
                        $tax_query[] = [
                            'taxonomy' => $tax,
                            'field'    => 'slug',
                            'terms'    => $values,
                            'operator' => 'IN',
                        ];
                    }

                    // текущий термин
                    $tax_query[] = [
                        'taxonomy' => $taxonomy,
                        'field'    => 'slug',
                        'terms'    => $term->slug,
                    ];

                    // 🔥 LOG: проверка каждого термина
                    cwc_log('CHECK TERM', [
                        'taxonomy' => $taxonomy,
                        'term'     => $term->slug,
                    ]);

                    $query = new WP_Query([
                        'post_type' => 'product',
                        'posts_per_page' => 1,
                        'tax_query' => $tax_query,
                    ]);

                    if (!$query->found_posts) continue;
                ?>

                    <?php
                    $is_active = isset($active_filters[$taxonomy]) && in_array($term->slug, $active_filters[$taxonomy]);
                    ?>

                    <li>
                        <a href="#"
                            class="filter-item <?php echo $is_active ? 'active' : ''; ?>"
                            data-slug="<?php echo esc_attr($term->slug); ?>"
                            data-taxonomy="<?php echo esc_attr($taxonomy); ?>">
                            <?php echo esc_html($term->name); ?>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>
        </div>
    </div>

<?php
    return ob_get_clean();
}

/* ---------------------------------------------------
 * Рендер всех фильтров
 * --------------------------------------------------- */
function cwc_render_filters_with_context($current_cat_id, $active_filters = [])
{
    $taxonomies = [
        'pa_czvet' => 'Цвет',
        'pa_podkluchenie' => 'Подключение',
        'pa_kolichestvo-sec' => 'Количество секций',
        'pa_pl-obogreva' => 'Площадь обогрева',
        'pa_teplootdacha' => 'Теплоотдача',
    ];

    $html = '';

    foreach ($taxonomies as $taxonomy => $title) {

        $filters_for_this = $active_filters;
        unset($filters_for_this[$taxonomy]);

        $html .= cwc_render_attribute_filter_dynamic(
            $taxonomy,
            $title,
            $current_cat_id,
            $filters_for_this
        );
    }

    $html .= '
        <div class="cwc-filter-actions">
            <button id="cwc-reset-filters" class="cwc-reset-filters">
                Сбросить фильтры
            </button>
        </div>
    ';

    return $html;
}

/* ---------------------------------------------------
 * ШОРТКОД
 * --------------------------------------------------- */
function cwc_shop_filters_shortcode()
{
    $current_cat_id = is_product_category() ? get_queried_object_id() : 0;

    ob_start(); ?>

    <div class="sidebar-area-wrapper _filters"
        data-current-cat="<?php echo esc_attr($current_cat_id); ?>">

        <?php
        echo cwc_render_filters_with_context($current_cat_id);
        ?>

    </div>

<?php
    return ob_get_clean();
}
add_shortcode('shop_filters', 'cwc_shop_filters_shortcode');

/* ---------------------------------------------------
 * Default attributes для вариативных товаров
 * --------------------------------------------------- */
function cwc_get_default_attributes_meta_query($active_filters)
{
    $meta_query = [];

    foreach ($active_filters as $taxonomy => $terms) {

        $meta_key = 'attribute_' . $taxonomy;

        $meta_query[] = [
            'key'     => $meta_key,
            'value'   => $terms,
            'compare' => 'IN',
        ];
    }

    // 🔥 LOG: meta query генерация
    cwc_log('DEFAULT META QUERY', $meta_query);

    return $meta_query;
}

/* ---------------------------------------------------
 * AJAX
 * --------------------------------------------------- */
add_action('wp_ajax_cwc_filter_products', 'cwc_filter_products');
add_action('wp_ajax_nopriv_cwc_filter_products', 'cwc_filter_products');

function cwc_filter_products()
{
    // 🔥 LOG: raw request
    cwc_log('RAW REQUEST', $_POST);

    $current_cat_id = !empty($_POST['current_cat_id']) ? (int) $_POST['current_cat_id'] : 0;
    $active_filters = cwc_get_active_filters();

    // 🔥 LOG: parsed filters
    cwc_log('ACTIVE FILTERS', $active_filters);

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'tax_query'      => ['relation' => 'AND'],
        'meta_query'     => ['relation' => 'AND'],
    ];

    // категория
    if ($current_cat_id) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $current_cat_id,
        ];
    }

    // атрибуты
    foreach ($active_filters as $taxonomy => $terms) {

        $args['tax_query'][] = [
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => array_map('wc_clean', $terms),
            'operator' => 'IN',
        ];

        // 🔥 LOG: tax query step
        cwc_log('TAX QUERY STEP', [
            'taxonomy' => $taxonomy,
            'terms'    => $terms
        ]);
    }

    // 🔥 default attributes
    $default_meta_query = cwc_get_default_attributes_meta_query($active_filters);

    if (!empty($default_meta_query)) {

        $meta_block = [
            'relation' => 'OR',
            [
                'key'     => '_product_type',
                'compare' => 'NOT EXISTS',
            ],
            ...$default_meta_query
        ];

        $args['meta_query'][] = $meta_block;

        cwc_log('META QUERY BLOCK', $meta_block);
    }

    // сортировка
    $ordering = WC()->query->get_catalog_ordering_args($_POST['orderby'] ?? '');
    $args['orderby'] = $ordering['orderby'];
    $args['order']   = $ordering['order'];

    if (!empty($ordering['meta_key'])) {
        $args['meta_key'] = $ordering['meta_key'];
    }

    // 🔥 LOG FINAL ARGS
    cwc_log('FINAL WP_QUERY ARGS', $args);

    $query = new WP_Query($args);

    cwc_log('FOUND POSTS', $query->found_posts);

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

    $filters_html = cwc_render_filters_with_context($current_cat_id, $active_filters);

    wp_send_json_success([
        'html'    => ob_get_clean(),
        'filters' => $filters_html
    ]);
}
