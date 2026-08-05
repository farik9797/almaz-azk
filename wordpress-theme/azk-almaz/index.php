<?php if (!defined('ABSPATH')) exit; ?>
<?php get_header(); ?>

<main class="max-w-3xl mx-auto px-4 py-16">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article class="prose max-w-none">
      <h1 class="text-2xl font-extrabold text-navy"><?php the_title(); ?></h1>
      <?php the_content(); ?>
    </article>
  <?php endwhile; else: ?>
    <p>Ничего не найдено.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
