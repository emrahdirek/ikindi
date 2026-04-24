<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- TOP BAR -->
<div class="topbar">
  <div class="container topbar-inner">
    <div class="topbar-left">
      <a href="tel:+905056625800">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
        +90 505 662 58 00
      </a>
      <a href="mailto:info@bilisim.k12.tr">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        info@bilisim.k12.tr
      </a>
    </div>
    <div class="topbar-right">
      <?php
      wp_nav_menu([
        'theme_location' => 'topbar',
        'container'      => false,
        'menu_class'     => '',
        'fallback_cb'    => false,
        'items_wrap'     => '%3$s',
        'walker'         => new Bilisim_Topbar_Walker(),
      ]);
      ?>
      <a href="<?php echo esc_url(home_url('/kayit')); ?>" style="background:var(--cyan);color:white;padding:.25rem .8rem;border-radius:20px;font-weight:700;">Kayıt Ol</a>
    </div>
  </div>
</div>

<!-- HEADER -->
<header class="header">
  <nav class="nav-wrap">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
      <?php if (has_custom_logo()): the_custom_logo(); else: ?>
      <div class="logo-mark">
        <svg viewBox="0 0 24 24" fill="white"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
      </div>
      <div class="logo-text">
        <strong><?php bloginfo('name'); ?></strong>
        <span><?php bloginfo('description'); ?></span>
      </div>
      <?php endif; ?>
    </a>

    <?php
    wp_nav_menu([
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => 'nav-links',
      'fallback_cb'    => 'bilisim_fallback_menu',
      'walker'         => new Bilisim_Nav_Walker(),
    ]);
    ?>

    <div class="nav-cta">
      <a href="<?php echo esc_url(home_url('/kampus-turu')); ?>" class="btn btn-ghost" style="padding:.5rem 1rem;font-size:.82rem;">Kampüs Turu</a>
      <a href="<?php echo esc_url(home_url('/kayit')); ?>" class="btn btn-primary" style="padding:.5rem 1rem;font-size:.82rem;">Kayıt Ol</a>
    </div>

    <button class="hamburger" aria-label="Menü">
      <span></span><span></span><span></span>
    </button>
  </nav>
</header>

<!-- MOBILE NAV -->
<div class="overlay"></div>
<nav class="mobile-nav" aria-label="Mobil menü">
  <button class="mobile-nav-close" aria-label="Kapat">✕</button>
  <?php
  wp_nav_menu([
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'mobile-nav-links',
    'fallback_cb'    => false,
  ]);
  ?>
  <div class="mobile-cta">
    <a href="<?php echo esc_url(home_url('/kayit')); ?>" class="btn btn-primary" style="justify-content:center;">Kayıt Ol</a>
    <a href="<?php echo esc_url(home_url('/kampus-turu')); ?>" class="btn btn-ghost" style="justify-content:center;">Kampüs Turu</a>
  </div>
</nav>

<!-- ANNOUNCEMENT -->
<div class="announce">
  🎓 2025–2026 Bursluluk Sınav Sonuçları Açıklandı!
  <a href="<?php echo esc_url(home_url('/burs')); ?>">Sonuçları Gör →</a>
  <span class="announce-close">✕</span>
</div>
