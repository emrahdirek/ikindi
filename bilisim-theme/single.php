<?php get_header(); ?>

<main style="padding:5rem 0;">
  <div class="container" style="max-width:820px;">
    <?php while (have_posts()): the_post(); ?>
    <article>
      <div class="news-meta" style="margin-bottom:1rem;">
        <span class="news-cat"><?php echo get_the_category_list(', ') ?: 'Haber'; ?></span>
        <span class="news-date"><?php echo get_the_date('d F Y'); ?></span>
      </div>
      <h1 style="color:var(--navy);margin-bottom:1.5rem;"><?php the_title(); ?></h1>
      <?php if (has_post_thumbnail()): ?>
        <div style="margin-bottom:2rem;border-radius:12px;overflow:hidden;">
          <?php the_post_thumbnail('large', ['style'=>'width:100%;height:400px;object-fit:cover;']); ?>
        </div>
      <?php endif; ?>
      <div style="font-size:1.05rem;line-height:1.9;color:var(--dark);">
        <?php the_content(); ?>
      </div>
    </article>
    <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid #e2e8f0;">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-ghost">← Anasayfaya Dön</a>
    </div>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>
