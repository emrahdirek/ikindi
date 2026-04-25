<?php
defined('ABSPATH') || exit;

require_once get_template_directory() . '/inc/walkers.php';

function bilisim_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
  add_theme_support('custom-logo');
  register_nav_menus(['primary' => 'Ana Menü', 'footer' => 'Footer Menü']);
}
add_action('after_setup_theme', 'bilisim_setup');

function bilisim_scripts() {
  wp_enqueue_style('bilisim-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800;900&family=Inter:wght@400;500;600;700&display=swap', [], null);
  wp_enqueue_style('bilisim-style', get_template_directory_uri() . '/assets/css/style.css', ['bilisim-fonts'], '3.0.0');
  wp_enqueue_script('bilisim-main', get_template_directory_uri() . '/assets/js/main.js', [], '2.0.0', true);
}
add_action('wp_enqueue_scripts', 'bilisim_scripts');

/* Remove ALL WordPress fingerprints */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
add_filter('the_generator', '__return_empty_string');
add_filter('show_admin_bar', '__return_false');

/* Remove wp-embed script */
add_action('wp_footer', function() { wp_deregister_script('wp-embed'); });

/* Custom login redirect (disguise wp-admin) */
add_action('login_head', function() {
  echo '<style>body.login{background:#050d1f;} .login h1 a{display:none}</style>';
});

/* Clean wp_head */
add_filter('wp_headers', function($headers) {
  unset($headers['X-Pingback']);
  return $headers;
});

/* Remove version from scripts/styles */
add_filter('style_loader_src',  function($src) { return remove_query_arg('ver', $src); });
add_filter('script_loader_src', function($src) { return remove_query_arg('ver', $src); });

/* Excerpt */
add_filter('excerpt_length', fn() => 18);
add_filter('excerpt_more',   fn() => '…');
