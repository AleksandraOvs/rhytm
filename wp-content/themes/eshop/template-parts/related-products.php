<!-- ================= Products from same category ================= -->
<?php
// Получаем категории текущего товара
$terms = wp_get_post_terms($product->get_id(), 'product_cat');

if (!empty($terms)) {

    $term_ids = wp_list_pluck($terms, 'term_id');

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'post__not_in'   => [$product->get_id()], // исключаем текущий товар
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $term_ids,
            ],
        ],
    ];

    $products = new WP_Query($args);

    if ($products->have_posts()) : ?>
        <div class="single-product__related">
            <div class="relative-products__head">
                <h2>Похожие товары</h2>
            </div>

            <?php
            wc_set_loop_prop('columns', 4);
            ?>

            <div class="products-on-column">
                <?php while ($products->have_posts()) : $products->the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </div>

            <?php wp_reset_postdata(); ?>
        </div>
<?php endif;
}
?>