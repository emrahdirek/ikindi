<?php get_header(); ?>

<!-- HERO -->
<section class="hero">
  <div class="container">
    <div class="hero-inner">
      <div class="hero-left">
        <div class="hero-badge">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
          Yapay Zeka Destekli Eğitim · Ankara
        </div>
        <h1>Geleceği <span>Kodlayan</span><br>Nesiller Burada!</h1>
        <p class="hero-sub">Yenilikçi eğitim modelimiz, teknoloji altyapımız ve değer odaklı yaklaşımımızla çocuğunuzu geleceğe hazırlıyoruz. Anaokulu'ndan Lise'ye — üç kampüste.</p>
        <div class="hero-btns">
          <a href="<?php echo esc_url(home_url('/kayit')); ?>" class="btn btn-cyan">Kayıt Bilgisi Al</a>
          <a href="<?php echo esc_url(home_url('/kampus-turu')); ?>" class="btn btn-outline">Kampüs Turu Planla</a>
        </div>
      </div>
      <div class="hero-card-wrap">
        <div class="hero-stat-card">
          <div class="icon">🏆</div>
          <span class="num" data-target="98" data-suffix="%">0%</span>
          <span class="lbl">LGS Başarı Oranı</span>
        </div>
        <div class="hero-stat-card">
          <div class="icon">🎓</div>
          <span class="num" data-target="250" data-suffix="+">0+</span>
          <span class="lbl">TÜBİTAK Projesi</span>
        </div>
        <div class="hero-stat-card">
          <div class="icon">🤖</div>
          <span class="num" data-target="3">0</span>
          <span class="lbl">Aktif Kampüs</span>
        </div>
        <div class="hero-stat-card">
          <div class="icon">🌍</div>
          <span class="num" data-target="90" data-suffix="%">0%</span>
          <span class="lbl">Öğretmen Sürekliliği</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHY US -->
<section class="section">
  <div class="container">
    <div class="why-grid">
      <div class="why-img">
        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=700&q=80" alt="Bilişim Koleji öğrencileri" loading="lazy">
        <div class="why-float">
          <span class="n">37+</span>
          <span class="t">Yıllık<br>Deneyim</span>
        </div>
        <div class="why-float2">
          <span class="n">🤖 KAİZEN</span>
          <span class="t">Türkiye'nin ilk<br>YZ öğrencisi</span>
        </div>
      </div>
      <div class="why-text">
        <span class="section-label">Neden Bilişim Koleji?</span>
        <h2 style="color:var(--navy)">Teknoloji ve İnsani<br>Değerlerin Buluşması</h2>
        <div class="divider divider-left"></div>
        <p class="lead">Yapay Zeka Destekli, Değer Odaklı Eğitim Ekosistemi anlayışıyla her öğrenciyi biricik kabul ediyoruz. Akademik başarı, teknoloji okuryazarlığı ve güçlü karakter bir arada.</p>
        <div class="why-list">
          <div class="why-item">
            <div class="why-icon">
              <svg viewBox="0 0 24 24" fill="var(--blue)"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
            </div>
            <div class="why-item-text">
              <h4>STEM & Kodlama Laboratuvarları</h4>
              <p>Her kademedeki öğrenciler için donanımlı kodlama ve robotik atölyeleri.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-icon">
              <svg viewBox="0 0 24 24" fill="var(--blue)"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div class="why-item-text">
              <h4>Güvenli ve Destekleyici Ortam</h4>
              <p>Rehberlik ve psikolojik danışmanlık hizmetleriyle her öğrenci değerli.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-icon">
              <svg viewBox="0 0 24 24" fill="var(--blue)"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M9 7a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/></svg>
            </div>
            <div class="why-item-text">
              <h4>%90 Öğretmen Sürekliliği</h4>
              <p>Deneyimli, köklü öğretmen kadrosuyla yıllara yayılan güven ilişkisi.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-icon">
              <svg viewBox="0 0 24 24" fill="var(--blue)"><path d="M3 5h12M3 8h9m-9 3h6m4 0l4-4 4 4m-4-4v12"/></svg>
            </div>
            <div class="why-item-text">
              <h4>Çok Dilli Eğitim</h4>
              <p>İngilizce yoğun program ve seçmeli yabancı dil dersleriyle küresel bakış.</p>
            </div>
          </div>
        </div>
        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
          <a href="<?php echo esc_url(home_url('/hakkimizda')); ?>" class="btn btn-primary">Daha Fazla Bilgi</a>
          <a href="<?php echo esc_url(home_url('/kampus-turu')); ?>" class="btn btn-ghost">Kampüs Turu Al</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- EDUCATION LEVELS -->
<section class="levels section">
  <div class="container">
    <div class="text-center">
      <span class="section-label">Eğitim Kademeleri</span>
      <h2 style="color:var(--navy)">Anaokulu'ndan Lise'ye<br>Kesintisiz Eğitim</h2>
      <div class="divider"></div>
      <p style="max-width:580px;margin:0 auto;color:var(--gray);">Her yaş grubuna özel, teknoloji destekli ve değer odaklı müfredat programlarımızla çocuğunuzun potansiyelini açığa çıkarıyoruz.</p>
    </div>
    <div class="levels-grid">
      <div class="level-card">
        <div class="level-img">
          <img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=500&q=80" alt="Anaokulu" loading="lazy">
          <span class="level-tag">3 – 6 Yaş</span>
        </div>
        <div class="level-body">
          <h3>Anaokulu</h3>
          <p>Oyun temelli öğrenme, ilk kodlama deneyimleri ve duygusal zeka gelişimiyle çocuğunuzun merakını besleyin.</p>
          <a href="<?php echo esc_url(home_url('/anaokulu')); ?>">Keşfet →</a>
        </div>
      </div>
      <div class="level-card">
        <div class="level-img">
          <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?w=500&q=80" alt="İlkokul" loading="lazy">
          <span class="level-tag">1. – 4. Sınıf</span>
        </div>
        <div class="level-body">
          <h3>İlkokul</h3>
          <p>Sağlam akademik temel, erken yabancı dil eğitimi ve STEM atölyeleriyle öğrenmeyi sevdiriyoruz.</p>
          <a href="<?php echo esc_url(home_url('/ilkokul')); ?>">Keşfet →</a>
        </div>
      </div>
      <div class="level-card">
        <div class="level-img">
          <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=500&q=80" alt="Ortaokul" loading="lazy">
          <span class="level-tag">5. – 8. Sınıf</span>
        </div>
        <div class="level-body">
          <h3>Ortaokul</h3>
          <p>LGS'ye güçlü hazırlık, robotik, TÜBİTAK projeleri ve kulüp etkinlikleriyle bütünsel gelişim.</p>
          <a href="<?php echo esc_url(home_url('/ortaokul')); ?>">Keşfet →</a>
        </div>
      </div>
      <div class="level-card">
        <div class="level-img">
          <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=500&q=80" alt="Lise" loading="lazy">
          <span class="level-tag">9. – 12. Sınıf</span>
        </div>
        <div class="level-body">
          <h3>Lise</h3>
          <p>YKS'de üst sıra başarıları, yapay zeka dersleri, uluslararası sertifikalar ve kariyer rehberliği.</p>
          <a href="<?php echo esc_url(home_url('/lise')); ?>">Keşfet →</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- EDUCATION MODEL -->
<section class="model section">
  <div class="container">
    <div class="text-center">
      <span class="section-label" style="color:var(--cyan)">Eğitim Modelimiz</span>
      <h2 class="text-white">Yenilikçi &amp; Bütünsel<br>Eğitim Yaklaşımı</h2>
      <div class="divider"></div>
    </div>
    <div class="model-grid">
      <div class="model-card">
        <div class="model-icon"><svg viewBox="0 0 24 24" fill="white"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg></div>
        <h3>STEM & Kodlama</h3>
        <p>Algoritma, robotik, yapay zeka ve proje tabanlı öğrenmeyle geleceğin mesleklerine hazırlık.</p>
      </div>
      <div class="model-card">
        <div class="model-icon"><svg viewBox="0 0 24 24" fill="white"><path d="M5 3l14 9-14 9V3z"/></svg></div>
        <h3>Yabancı Dil</h3>
        <p>Yoğun İngilizce programı, native speaker öğretmenler ve dil sertifikası hazırlık kursları.</p>
      </div>
      <div class="model-card">
        <div class="model-icon"><svg viewBox="0 0 24 24" fill="white"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
        <h3>Değerler Eğitimi</h3>
        <p>Sorumluluk, empati, dürüstlük ve milli değerleri özümseyen bilinçli bireyler yetiştiriyoruz.</p>
      </div>
      <div class="model-card">
        <div class="model-icon"><svg viewBox="0 0 24 24" fill="white"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M9 7a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/></svg></div>
        <h3>Rehberlik & Koçluk</h3>
        <p>Bireysel kariyer planlaması, psikolojik destek ve aile danışmanlığıyla tam kapsamlı rehberlik.</p>
      </div>
      <div class="model-card">
        <div class="model-icon"><svg viewBox="0 0 24 24" fill="white"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div>
        <h3>Atölyeler & Kulüpler</h3>
        <p>Satranç, resim, müzik, drama, girişimcilik ve 30'dan fazla kulüpte sosyal gelişim.</p>
      </div>
    </div>
  </div>
</section>

<!-- KAIZEN -->
<section class="kaizen section">
  <div class="container">
    <div class="kaizen-inner">
      <div class="kaizen-text">
        <div class="badge">🤖 Türkiye'nin İlki</div>
        <h2>KAİZEN — Yapay Zeka<br>Öğrencimiz</h2>
        <p>Bilişim Koleji, Türkiye'nin ilk yapay zeka öğrencisi KAİZEN'i hayata geçirdi. KAİZEN, öğrencilerle birlikte öğreniyor, proje geliştiriyor ve okulun dijital dönüşümüne katkı sağlıyor.</p>
        <div class="kaizen-feats">
          <div class="kaizen-feat"><span>🧠</span> Makine öğrenimi tabanlı</div>
          <div class="kaizen-feat"><span>📊</span> Veri analizi yapabiliyor</div>
          <div class="kaizen-feat"><span>🤝</span> Öğrencilerle iş birliği</div>
          <div class="kaizen-feat"><span>🌐</span> Çok dilli iletişim</div>
          <div class="kaizen-feat"><span>🔬</span> Bilim projelerinde aktif</div>
          <div class="kaizen-feat"><span>📱</span> Uygulama geliştiriyor</div>
        </div>
        <a href="<?php echo esc_url(home_url('/kaizen')); ?>" class="btn btn-cyan">KAİZEN Hakkında Daha Fazla</a>
      </div>
      <div class="kaizen-visual">
        <div class="kaizen-avatar">🤖</div>
        <div class="kaizen-name">KAİZEN</div>
        <div class="kaizen-role">Yapay Zeka Öğrenci · Bilişim Koleji</div>
        <div class="kaizen-tags">
          <span class="kaizen-tag">Python</span>
          <span class="kaizen-tag">Machine Learning</span>
          <span class="kaizen-tag">NLP</span>
          <span class="kaizen-tag">Robotics</span>
          <span class="kaizen-tag">Data Science</span>
          <span class="kaizen-tag">IoT</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="stats">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-item">
        <span class="num" data-target="98" data-suffix="%">98%</span>
        <span class="lbl">LGS Başarı Oranı</span>
      </div>
      <div class="stat-item">
        <span class="num" data-target="250" data-suffix="+">250+</span>
        <span class="lbl">TÜBİTAK Projesi</span>
      </div>
      <div class="stat-item">
        <span class="num" data-target="90" data-suffix="%">90%</span>
        <span class="lbl">Öğretmen Sürekliliği</span>
      </div>
      <div class="stat-item">
        <span class="num" data-target="3">3</span>
        <span class="lbl">Aktif Kampüs</span>
      </div>
    </div>
  </div>
</section>

<!-- CAMPUSES -->
<section class="campuses section">
  <div class="container">
    <div class="text-center">
      <span class="section-label">Kampüslerimiz</span>
      <h2 style="color:var(--navy)">Ankara'nın Üç Noktasında<br>Modern Eğitim</h2>
      <div class="divider"></div>
    </div>
    <div class="campus-grid">
      <div class="campus-card">
        <div class="campus-img">
          <img src="https://images.unsplash.com/photo-1562774053-701939374585?w=600&q=80" alt="Yeni Nesil Kampüs" loading="lazy">
          <div class="campus-label"><h3>Yeni Nesil Eğitim Kampüsü</h3><span>Keçiören / Pursaklar</span></div>
        </div>
        <div class="campus-body">
          <div class="campus-feats">
            <div class="campus-feat">Olimpik yüzme havuzu</div>
            <div class="campus-feat">Tam donanımlı STEM laboratuvarı</div>
            <div class="campus-feat">Spor kompleksi &amp; atletizm pisti</div>
            <div class="campus-feat">Akıllı sınıf teknolojisi</div>
          </div>
          <a href="<?php echo esc_url(home_url('/yeni-nesil-kampus')); ?>">Kampüsü Keşfet →</a>
        </div>
      </div>
      <div class="campus-card">
        <div class="campus-img">
          <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80" alt="Çankaya Kampüs" loading="lazy">
          <div class="campus-label"><h3>Çankaya Şubesi</h3><span>Çankaya / Ankara Merkez</span></div>
        </div>
        <div class="campus-body">
          <div class="campus-feats">
            <div class="campus-feat">Merkezi konum, ulaşım avantajı</div>
            <div class="campus-feat">Güçlü akademik başarı geçmişi</div>
            <div class="campus-feat">Çok amaçlı konferans salonu</div>
            <div class="campus-feat">Yabancı dil atölyeleri</div>
          </div>
          <a href="<?php echo esc_url(home_url('/cankaya-kampus')); ?>">Kampüsü Keşfet →</a>
        </div>
      </div>
      <div class="campus-card">
        <div class="campus-img">
          <img src="https://images.unsplash.com/photo-1537495329792-41ae41ad3bf0?w=600&q=80" alt="Hüseyingazi Kampüs" loading="lazy">
          <div class="campus-label"><h3>Hüseyingazi Şubesi</h3><span>Mamak / Hüseyingazi</span></div>
        </div>
        <div class="campus-body">
          <div class="campus-feats">
            <div class="campus-feat">Doğayla iç içe kampüs</div>
            <div class="campus-feat">Açık hava etkinlik alanları</div>
            <div class="campus-feat">Modern müzik &amp; sanat stüdyoları</div>
            <div class="campus-feat">Organik bahçe &amp; tarım projesi</div>
          </div>
          <a href="<?php echo esc_url(home_url('/huseyingazi-kampus')); ?>">Kampüsü Keşfet →</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NEWS — WordPress Posts -->
<section class="news section" style="background:var(--light)">
  <div class="container">
    <div class="news-header-row">
      <div>
        <span class="section-label">Haberler</span>
        <h2 style="color:var(--navy)">Bilişim Koleji'nden<br>Son Gelişmeler</h2>
      </div>
      <a href="<?php echo esc_url(home_url('/haberler')); ?>" class="btn btn-primary" style="padding:.55rem 1.2rem;font-size:.84rem;">Tüm Haberler</a>
    </div>
    <div class="news-grid">
      <?php
      $posts = new WP_Query(['posts_per_page' => 3, 'post_status' => 'publish']);
      if ($posts->have_posts()):
        while ($posts->have_posts()): $posts->the_post(); ?>
        <div class="news-card">
          <div class="news-img">
            <?php if (has_post_thumbnail()): ?>
              <?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
            <?php else: ?>
              <img src="https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=500&q=80" alt="<?php the_title_attribute(); ?>" loading="lazy">
            <?php endif; ?>
          </div>
          <div class="news-body">
            <div class="news-meta">
              <span class="news-cat"><?php echo esc_html(get_the_category_list(', ') ?: 'Haber'); ?></span>
              <span class="news-date"><?php echo get_the_date('d F Y'); ?></span>
            </div>
            <h3><?php the_title(); ?></h3>
            <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
            <a href="<?php the_permalink(); ?>">Devamını Oku →</a>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata();
      else: ?>
        <!-- Fallback static posts -->
        <div class="news-card">
          <div class="news-img"><img src="https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=500&q=80" alt="" loading="lazy"></div>
          <div class="news-body">
            <div class="news-meta"><span class="news-cat">Teknoloji</span><span class="news-date">18 Nisan 2026</span></div>
            <h3>Dijital Dünyanın İhtiyaç Duyduğu Bireyleri Yetiştiriyoruz</h3>
            <p>KAİZEN projesiyle öğrencilerimiz yapay zeka uygulamaları geliştiriyor.</p>
            <a href="#">Devamını Oku →</a>
          </div>
        </div>
        <div class="news-card">
          <div class="news-img"><img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=500&q=80" alt="" loading="lazy"></div>
          <div class="news-body">
            <div class="news-meta"><span class="news-cat">Eğitim</span><span class="news-date">10 Nisan 2026</span></div>
            <h3>Özgüvenli Konuşma Becerileri Geliştirme Atölyesi</h3>
            <p>Sunum teknikleri ve etkili iletişim atölyemizde öğrencilerimiz özgüvenlerini pekiştirdi.</p>
            <a href="#">Devamını Oku →</a>
          </div>
        </div>
        <div class="news-card">
          <div class="news-img"><img src="https://images.unsplash.com/photo-1518020382113-a7e8fc38eac9?w=500&q=80" alt="" loading="lazy"></div>
          <div class="news-body">
            <div class="news-meta"><span class="news-cat">Gelişim</span><span class="news-date">2 Nisan 2026</span></div>
            <h3>Hayal Gücünü Geliştirmenin Yolları: Yaratıcı Düşünce Kampı</h3>
            <p>3 günlük kamp programında öğrencilerimiz tasarım odaklı düşünme becerilerini geliştirdi.</p>
            <a href="#">Devamını Oku →</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-band">
  <div class="container">
    <span class="section-label">Harekete Geç</span>
    <h2>Çocuğunuzun Geleceğini<br>Birlikte Şekillendirelim</h2>
    <p>Kayıt bilgisi almak, kampüs turu planlamak veya bursluluk sınavına başvurmak için hemen iletişime geçin.</p>
    <div class="cta-btns">
      <a href="<?php echo esc_url(home_url('/kayit')); ?>" class="btn btn-cyan">Kayıt Ol</a>
      <a href="<?php echo esc_url(home_url('/kampus-turu')); ?>" class="btn btn-outline">Kampüs Turu</a>
      <a href="<?php echo esc_url(home_url('/burs')); ?>" class="btn btn-outline">Bursluluk Sınavı</a>
    </div>
  </div>
</section>

<!-- PARTNERS -->
<section class="partners">
  <div class="container">
    <h3>Anlaşmalı Kurum ve Partnerlerimiz</h3>
    <div class="partners-row">
      <div class="partner-logo">TÜBİTAK</div>
      <div class="partner-logo">MEB</div>
      <div class="partner-logo">ODTÜ</div>
      <div class="partner-logo">Hacettepe Üniv.</div>
      <div class="partner-logo">Bilkent</div>
      <div class="partner-logo">Microsoft Edu</div>
      <div class="partner-logo">Google For Edu</div>
      <div class="partner-logo">Robotel</div>
      <div class="partner-logo">Lego Education</div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
