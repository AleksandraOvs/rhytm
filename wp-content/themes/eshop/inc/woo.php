<?php
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

add_action('woocommerce_after_shop_loop_item', 'my_custom_add_to_cart_button', 10);

function my_custom_add_to_cart_button()
{
    global $product;

    if (!$product) return;

    $in_cart = false;

    foreach (WC()->cart->get_cart() as $cart_item) {
        if (
            $cart_item['product_id'] == $product->get_id() ||
            $cart_item['variation_id'] == $product->get_id()
        ) {
            $in_cart = true;
            break;
        }
    }

    $classes = 'button add_to_cart_button ajax_add_to_cart';
    if ($in_cart) {
        $classes .= ' in-cart';
    }

?>
    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
        data-product_id="<?php echo esc_attr($product->get_id()); ?>"
        data-quantity="1"
        class="<?php echo esc_attr($classes); ?>"
        <?php echo $in_cart ? 'disabled' : ''; ?>>

        <?php echo $in_cart ? 'В корзине' : 'В корзину'; ?>
    </a>
<?php
}


/* ---------- Cart count fragment ---------- */
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    ob_start();
?>
    <span class="cart-count"><?= WC()->cart->get_cart_contents_count(); ?></span>
<?php
    $fragments['span.cart-count'] = ob_get_clean();
    return $fragments;
});
