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

/* ---------- Убираем табы ---------- */
//add_filter('woocommerce_product_tabs', '__return_empty_array', 98);

/* ---------- Добавляем описание и характеристики под .product_meta ---------- */
// add_action('woocommerce_single_product_summary', 'add_content_after_product_meta', 45);

// function add_content_after_product_meta()
// {
//     global $product;

//     echo '<div class="product-meta-extra">';

//     // 📄 Описание
//     if ($product->get_description()) {
//         echo '<div class="product-description">';
//         echo '<h3>Описание</h3>';
//         echo wpautop($product->get_description());
//         echo '</div>';
//     }

//     // ⚙️ Характеристики
//     if ($product->has_attributes()) {
//         echo '<div class="product-attributes">';
//         echo '<h3>Характеристики</h3>';
//         wc_display_product_attributes($product);
//         echo '</div>';
//     }

//     echo '</div>';
// }

//убрать название товара на странице
//remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

//выводим первые три характеристики после заголовка товара в карточке
// add_action('woocommerce_single_product_summary', 'my_product_short_attributes', 6);

// function my_product_short_attributes()
// {
//     global $product;

//     if (!$product) return;

//     echo '<div class="product-short-attributes" id="js-product-attributes">';

//     $count = 0;

//     // ===== VARIABLE PRODUCT → дефолтная вариация =====
//     if ($product->is_type('variable')) {

//         $default_attrs = $product->get_default_attributes();

//         foreach ($default_attrs as $taxonomy => $term_slug) {

//             if ($count >= 3) break;

//             $label = wc_attribute_label($taxonomy);

//             $term = get_term_by('slug', $term_slug, $taxonomy);
//             $value = $term ? $term->name : $term_slug;

//             echo '<div class="attr">';
//             echo '<span class="attr-label">' . esc_html($label) . ':</span> ';
//             echo '<span class="attr-value">' . esc_html($value) . '</span>';
//             echo '</div>';

//             $count++;
//         }
//     } else {
//         // ===== SIMPLE PRODUCT =====

//         $attributes = $product->get_attributes();

//         foreach ($attributes as $attribute) {

//             if ($count >= 3) break;

//             if (!$attribute->get_visible()) continue;

//             $label = wc_attribute_label($attribute->get_name());

//             if ($attribute->is_taxonomy()) {
//                 $terms = wp_get_post_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
//                 $value = implode(', ', $terms);
//             } else {
//                 $value = implode(', ', $attribute->get_options());
//             }

//             if (!empty($value)) {
//                 echo '<div class="attr">';
//                 echo '<span class="attr-label">' . esc_html($label) . ':</span> ';
//                 echo '<span class="attr-value">' . esc_html($value) . '</span>';
//                 echo '</div>';

//                 $count++;
//             }
//         }
//     }

//     echo '</div>';
// }

//формат цены для вариативных товаров от...
add_filter('woocommerce_get_price_html', 'custom_variable_price_from', 10, 2);

function custom_variable_price_from($price, $product)
{

    if ($product->is_type('variable')) {

        // Минимальная цена вариации
        $min_price = $product->get_variation_price('min', true);

        if ($min_price) {
            $price = 'От ' . wc_price($min_price);
        }
    }

    return $price;
}

add_filter('gettext', function ($translated, $text, $domain) {

    // WooCommerce Blocks
    if ($text === 'Estimated total' || $text === 'estimated total') {
        return 'Итого';
    }

    return $translated;
}, 20, 3);
