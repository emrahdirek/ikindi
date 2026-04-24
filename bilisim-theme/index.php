<?php get_header(); ?>

<main style="padding:4rem 0;min-height:60vh;">
  <div class="container">
    <?php if (have_posts()): ?>
      <div class="news-grid">
        <?php while (have_posts()): the_post(); ?>
        <div class="news-card">
          <div class="news-img">
            <?php if (has_post_thumbnail()): the_post_thumbnail('medium_large', ['loading'=>'lazy']);
            else: ?><img src="https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=500&q=80" alt="" loading="lazy"><?php endif; ?>
          </div>
          <div class="news-body">
            <div class="news-meta">
              <span class="news-cat"><?php echo get_the_category_list(', ') ?: 'Genel'; ?></span>
              <span class="news-date"><?php echo get_the_date('d F Y'); ?></span>
            </div>
            <h3><?php the_title(); ?></h3>
            <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
            <a href="<?php the_permalink(); ?>">Devamını Oku →</a>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <div style="margin-top:3rem;text-align:center;">
        <?php the_posts_navigation(); ?>
      </div>
    <?php else: ?>
      <p style="text-align:center;color:var(--gray);padding:4rem 0;">Henüz içerik yok.</p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
