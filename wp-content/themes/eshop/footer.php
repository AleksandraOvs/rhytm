<div id="main-form" style="display:none;max-width:600px;">
    <?php
    if ($mainform_shortcode = carbon_get_theme_option('crb_mainform_shortcode')) {
        echo do_shortcode($mainform_shortcode);
    }
    ?>
</div>
<?php if (is_active_sidebar('eshop-footer-area')) { ?>
    <div id="content-footer-section" class="row clearfix">
        <?php
        // Calling the header sidebar if it exists.
        dynamic_sidebar('eshop-footer-area');
        ?>
    </div>
<?php } ?>
<footer id="colophon" class="footer" role="contentinfo">
    <div class="container">

        <div class="footer-brand">
            <?php
            $footer_logo = get_theme_mod('footer_logo');
            $img = wp_get_attachment_image_src($footer_logo, 'full');
            if ($img) : echo '<a class="custom-logo-link" href="' . site_url() . '"><img src="' . $img[0] . '" alt=""></a>';
            endif;
            ?>
        </div>

        <?php if (is_active_sidebar('footer-sidebar-1')) : ?>
            <div class="footer-col">
                <?php dynamic_sidebar('footer-sidebar-1'); ?>
            </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer-sidebar-2')) : ?>
            <div class="footer-col">
                <?php dynamic_sidebar('footer-sidebar-2'); ?>
            </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer-sidebar-3')) : ?>
            <div class="footer-col">
                <?php dynamic_sidebar('footer-sidebar-3'); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="container">
        <?php if (is_active_sidebar('footer-sidebar-4')) : ?>

            <?php dynamic_sidebar('footer-sidebar-4'); ?>

        <?php endif; ?>
    </div>
</footer>
<div id="back-top">
    <a href="#top">
        <span></span>
    </a>
</div>
</div>
<!-- end main container -->

<div class="current-temp"
    style="position: fixed;
  background: rgba(255,255,255,.7);
  color: #404040;
  padding: 5px 10px;
  font-size: 10px;
  bottom: 10px;
  right: 10px;">
    <?php echo get_current_template() ?>
</div>

<?php get_template_part('template-parts/toggle-contacts'); ?>

<?php get_template_part('template-parts/mobile-menu');
?>



<?php wp_footer(); ?>
</body>

</html>