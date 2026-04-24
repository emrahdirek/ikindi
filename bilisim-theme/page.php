<?php get_header(); ?>

<main style="padding:5rem 0;min-height:60vh;">
  <div class="container" style="max-width:900px;">
    <?php while (have_posts()): the_post(); ?>
    <h1 style="color:var(--navy);margin-bottom:2rem;"><?php the_title(); ?></h1>
    <?php if (has_post_thumbnail()): ?>
      <div style="margin-bottom:2rem;border-radius:12px;overflow:hidden;">
        <?php the_post_thumbnail('large', ['style'=>'width:100%;height:380px;object-fit:cover;']); ?>
      </div>
    <?php endif; ?>
    <div style="font-size:1.05rem;line-height:1.9;">
      <?php the_content(); ?>
    </div>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>
