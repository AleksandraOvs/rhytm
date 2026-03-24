<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package eshop
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'eshop'); ?></a>

        <header id="masthead" class="site-header">
            <div class="site-header-top">
                <div class="container">
                    <div class="site-branding">
                        <?php
                        $header_logo = get_theme_mod('header_logo');
                        $img = wp_get_attachment_image_src($header_logo, 'full');
                        if ($img) : echo '<a class="custom-logo-link" href="' . site_url() . '"><img src="' . $img[0] . '" alt=""></a>';
                        endif;
                        ?>
                    </div><!-- .site-branding -->


                    <?php get_template_part('template-parts/contacts') ?>

                    <nav id="site-navigation" class="main-navigation">
                        <div class="header-menu__inner">
                            <a href="/" class="button toggle-menu">Каталог</a>
                            <div class="header-menu">
                                <?php wp_nav_menu([
                                    'container' => false,
                                    'theme_location' => 'catalog_menu',
                                    //'walker' => new Custom_Walker_Nav_Menu,
                                    // 'depth' => 2,
                                ]); ?>
                            </div>

                        </div>
                        <div class="header-menu__inner">

                            <a href="/" class="button toggle-menu stroke-button">Меню</a>
                            <div class="header-menu">
                                <div class="close-menu">
                                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.708 12.616L0 11.908L5.6 6.308L0 0.708L0.708 0L6.308 5.6L11.908 0L12.616 0.708L7.016 6.308L12.616 11.908L11.908 12.616L6.308 7.016L0.708 12.616Z" fill="white" />
                                    </svg>
                                </div>
                                <?php wp_nav_menu([
                                    'container' => false,
                                    'theme_location' => 'header',
                                    // 'walker' => new Custom_Walker_Nav_Menu,
                                    // 'depth' => 2,
                                ]); ?>
                            </div>
                        </div>
                    </nav><!-- #site-navigation -->
                </div>
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </button>
            </div>

        </header><!-- #masthead -->