<?php
defined('ABSPATH') || exit;

require_once get_template_directory() . '/inc/walkers.php';

/* =====================
   THEME SETUP
===================== */
function bilisim_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo');
    add_theme_support('menus');

    register_nav_menus([
        'primary'  => __('Ana Menü', 'bilisim-koleji'),
        'topbar'   => __('Üst Bar Menü', 'bilisim-koleji'),
        'footer'   => __('Footer Menü', 'bilisim-koleji'),
    ]);

    load_theme_textdomain('bilisim-koleji', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'bilisim_setup');

/* =====================
   ENQUEUE ASSETS
===================== */
function bilisim_scripts() {
    wp_enqueue_style(
        'bilisim-google-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&family=Inter:wght@400;500;600&display=swap',
        [],
        null
    );
    wp_enqueue_style(
        'bilisim-main',
        get_template_directory_uri() . '/assets/css/style.css',
        ['bilisim-google-fonts'],
        '1.0.0'
    );
    wp_enqueue_script(
        'bilisim-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        '1.0.0',
        true
    );
    wp_localize_script('bilisim-main', 'bilisimData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('bilisim_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'bilisim_scripts');

/* =====================
   CUSTOM EXCERPT LENGTH
===================== */
function bilisim_excerpt_length() { return 20; }
add_filter('excerpt_length', 'bilisim_excerpt_length');

function bilisim_excerpt_more() { return '…'; }
add_filter('excerpt_more', 'bilisim_excerpt_more');

/* =====================
   WIDGET AREAS
===================== */
function bilisim_widgets_init() {
    register_sidebar([
        'name'          => __('Footer Widget 1', 'bilisim-koleji'),
        'id'            => 'footer-1',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 'bilisim_widgets_init');
