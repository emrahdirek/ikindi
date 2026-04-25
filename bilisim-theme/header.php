<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<?php wp_head(); ?>
</head>
<body <?php body_class('dark-theme'); ?>>
<?php wp_body_open(); ?>

<div id="cursor"></div>
<div id="cursor-ring"></div>
<canvas id="particle-canvas"></canvas>

<!-- ANNOUNCE -->
<div class="announce-bar" style="background:linear-gradient(90deg,#7c3aed,#00d4ff);color:#fff;text-align:center;padding:.65rem 3rem;font-size:.85rem;font-weight:600;position:relative;overflow:hidden;">
  🎓 2025–2026 Bursluluk Sınav Sonuçları Açıklandı! &nbsp;<a href="<?php echo esc_url(home_url('/burs')); ?>" style="color:#fff;text-decoration:underline;">Sonuçları Gör →</a>
  <span class="announce-close" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);cursor:none;font-size:1.1rem;opacity:.7;">✕</span>
</div>

<!-- HEADER -->
<header class="site-header">
  <div class="container header-inner">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
      <div class="logo-mark">
        <svg viewBox="0 0 24 24"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
      </div>
      <div>
        <span class="logo-name"><?php bloginfo('name'); ?></span>
        <span class="logo-sub">Geleceği Kodlayan Nesiller</span>
      </div>
    </a>

    <ul class="nav-links">
      <li class="nav-item">
        <a href="#">Kurumsal <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg></a>
        <ul class="dropdown">
          <li><a href="<?php echo esc_url(home_url('/hakkimizda')); ?>">Hakkımızda</a></li>
          <li><a href="<?php echo esc_url(home_url('/basarilari')); ?>">Başarılarımız</a></li>
          <li><a href="<?php echo esc_url(home_url('/kaizen')); ?>">KAİZEN — YZ Öğrencimiz</a></li>
          <li><a href="<?php echo esc_url(home_url('/anlasmalı-kurumlar')); ?>">Anlaşmalı Kurumlar</a></li>
          <li><a href="<?php echo esc_url(home_url('/kariyer')); ?>">Kariyer</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#">Kayıt & Burs <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg></a>
        <ul class="dropdown">
          <li><a href="<?php echo esc_url(home_url('/kayit')); ?>">Kayıt Bilgileri</a></li>
          <li><a href="<?php echo esc_url(home_url('/burs')); ?>">Bursluluk Sınavı</a></li>
          <li><a href="<?php echo esc_url(home_url('/ucretler')); ?>">Ücret Tablosu</a></li>
          <li><a href="<?php echo esc_url(home_url('/sss')); ?>">Sıkça Sorulan Sorular</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#">Eğitim <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg></a>
        <ul class="dropdown">
          <li><a href="<?php echo esc_url(home_url('/anaokulu')); ?>">Anaokulu</a></li>
          <li><a href="<?php echo esc_url(home_url('/ilkokul')); ?>">İlkokul</a></li>
          <li><a href="<?php echo esc_url(home_url('/ortaokul')); ?>">Ortaokul</a></li>
          <li><a href="<?php echo esc_url(home_url('/lise')); ?>">Lise</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#">Model <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg></a>
        <ul class="dropdown">
          <li><a href="<?php echo esc_url(home_url('/stem')); ?>">STEM & Kodlama</a></li>
          <li><a href="<?php echo esc_url(home_url('/dil')); ?>">Yabancı Dil</a></li>
          <li><a href="<?php echo esc_url(home_url('/rehberlik')); ?>">Rehberlik</a></li>
          <li><a href="<?php echo esc_url(home_url('/atolyeler')); ?>">Atölyeler & Kulüpler</a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#">Kampüsler <svg class="arr" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg></a>
        <ul class="dropdown">
          <li><a href="<?php echo esc_url(home_url('/yeni-nesil')); ?>">Yeni Nesil Kampüsü</a></li>
          <li><a href="<?php echo esc_url(home_url('/cankaya')); ?>">Çankaya Şubesi</a></li>
          <li><a href="<?php echo esc_url(home_url('/huseyingazi')); ?>">Hüseyingazi Şubesi</a></li>
        </ul>
      </li>
      <li class="nav-item"><a href="<?php echo esc_url(home_url('/iletisim')); ?>">İletişim</a></li>
    </ul>

    <div class="header-actions">
      <a href="<?php echo esc_url(home_url('/kampus-turu')); ?>" class="btn btn-ghost" style="padding:.5rem 1.1rem;font-size:.82rem;">Kampüs Turu</a>
      <a href="<?php echo esc_url(home_url('/kayit')); ?>" class="btn btn-glow" style="padding:.5rem 1.1rem;font-size:.82rem;">Kayıt Ol</a>
    </div>

    <button class="hamburger" aria-label="Menü"><span></span><span></span><span></span></button>
  </div>
</header>

<!-- MOBILE DRAWER -->
<div class="mobile-overlay"></div>
<nav class="mobile-drawer">
  <button class="drawer-close">✕</button>
  <ul class="drawer-links">
    <li><a href="#">Kurumsal</a></li>
    <li><a href="#">Kayıt & Burs</a></li>
    <li><a href="#">Eğitim Kademeleri</a></li>
    <li><a href="#">Eğitim Modeli</a></li>
    <li><a href="#">Kampüslerimiz</a></li>
    <li><a href="<?php echo esc_url(home_url('/iletisim')); ?>">İletişim</a></li>
  </ul>
  <div class="drawer-cta">
    <a href="<?php echo esc_url(home_url('/kayit')); ?>" class="btn btn-glow" style="justify-content:center;">Kayıt Ol</a>
    <a href="<?php echo esc_url(home_url('/kampus-turu')); ?>" class="btn btn-ghost" style="justify-content:center;">Kampüs Turu</a>
  </div>
</nav>
