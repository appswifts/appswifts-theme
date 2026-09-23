<?php
/**
 * Page template.
 */
get_header();

while (have_posts()) :
    the_post();
?>
  <header class="page-hero">
    <div class="wrap">
      <h1><?php the_title(); ?></h1>
      <?php if (has_excerpt()) : ?>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
    </div>
  </header>

  <div class="section">
    <div class="wrap">
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
<?php
endwhile;

get_footer();
