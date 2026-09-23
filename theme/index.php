<?php
/**
 * Fallback template — WordPress requires index.php to recognise a theme.
 */
get_header();
?>
  <div class="section">
    <div class="wrap">
      <?php if (have_posts()) : ?>
        <div class="grid grid--3">
          <?php while (have_posts()) : the_post(); ?>
            <article class="post-card">
              <div class="post-card__body">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="small muted"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
        <div class="pagination"><?php echo paginate_links(); ?></div>
      <?php else : ?>
        <div class="center">
          <h1><?php esc_html_e('Nothing found', 'appswifts'); ?></h1>
          <p><?php esc_html_e('Try the homepage or the blog.', 'appswifts'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php
get_footer();
