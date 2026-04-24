<?php
defined('ABSPATH') || exit;

/* Desktop Nav Walker */
class Bilisim_Nav_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="dropdown">';
    }
    function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $has_children = in_array('menu-item-has-children', $item->classes);
        $classes = $has_children ? 'nav-item' : 'nav-item';
        $output .= '<li class="' . esc_attr($classes) . '">';
        $output .= '<a href="' . esc_url($item->url) . '">';
        $output .= esc_html($item->title);
        if ($has_children) {
            $output .= '<svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>';
        }
        $output .= '</a>';
    }
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

/* Topbar Walker */
class Bilisim_Topbar_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {}
    function end_lvl(&$output, $depth = 0, $args = null) {}
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
    }
    function end_el(&$output, $item, $depth = 0, $args = null) {}
}

/* Fallback menu */
function bilisim_fallback_menu() {
    echo '<ul class="nav-links">';
    $pages = [
        'Kurumsal'          => '#',
        'Kayıt & Burs'      => '#',
        'Eğitim Kademeleri' => '#',
        'Eğitim Modeli'     => '#',
        'Kampüslerimiz'     => '#',
        'İletişim'          => '#',
    ];
    foreach ($pages as $label => $url) {
        echo '<li class="nav-item"><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}
