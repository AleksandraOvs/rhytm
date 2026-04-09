<?php
defined('ABSPATH') || exit;

global $product;

do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
    <h1 class="product-title"><?php the_title(); ?></h1>

    <div class="product-inner">
        <div class="product-inner__images">
            <?php do_action('woocommerce_before_single_product_summary'); ?>
        </div>

        <div class="product-inner__content">
            <?php do_action('woocommerce_single_product_summary'); ?>
        </div>
    </div>
</div>