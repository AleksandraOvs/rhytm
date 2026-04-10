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
    <!-- <h1 class="product-title"><?php //the_title(); 
                                    ?></h1> -->

    <div class="product-inner">
        <div class="product-inner__images">
            <?php do_action('woocommerce_before_single_product_summary'); ?>
        </div>

        <div class="product-inner__content">
            <?php do_action('woocommerce_single_product_summary'); ?>
        </div>
    </div>

    <div class="product-tabs">
        <div class="product-tabs__nav">
            <button class="product-tabs__btn active" data-tab="desc">Описание</button>
            <button class="product-tabs__btn" data-tab="chars">Характеристики</button>
        </div>

        <div class="product-tabs__content">
            <div class="product-tabs__pane active" data-tab="desc">
                <?php the_content(); ?>
            </div>

            <div class="product-tabs__pane" data-tab="chars">
                <?php wc_display_product_attributes($product); ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const tabs = document.querySelectorAll('.product-tabs__btn');
        const panes = document.querySelectorAll('.product-tabs__pane');

        tabs.forEach(btn => {
            btn.addEventListener('click', () => {
                const tab = btn.dataset.tab;

                // активная кнопка
                tabs.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // активный контент
                panes.forEach(pane => {
                    pane.classList.remove('active');
                    if (pane.dataset.tab === tab) {
                        pane.classList.add('active');
                    }
                });
            });
        });

    });
</script>