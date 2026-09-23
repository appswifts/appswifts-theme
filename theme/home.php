<?php
/**
 * Posts page (the /blog/ hub). WordPress uses home.php for page_for_posts.
 */
get_header();
?>
  <header class="page-hero">
    <div class="wrap">
      <span class="eyebrow"><?php esc_html_e('Insights', 'appswifts'); ?></span>
      <h1><?php esc_html_e('Practical guides for growing online in Africa', 'appswifts'); ?></h1>
      <p><?php esc_html_e('SEO, websites, digital marketing and AI. Written for business owners rather than developers.', 'appswifts'); ?></p>
    </div>
  </header>

  <div class="section">
    <div class="wrap">
      <?php if (have_posts()) : ?>
        <div class="grid grid--3">
          <?php while (have_posts()) : the_post(); ?>
            <article class="post-card">
              <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php echo get_the_post_thumbnail(null, 'medium_large'); ?>
              </a>
              <div class="post-card__body">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="small muted"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
        <div class="pagination">
          <?php echo paginate_links([
              'prev_text' => '&larr; ' . esc_html__('Newer', 'appswifts'),
              'next_text' => esc_html__('Older', 'appswifts') . ' &rarr;',
          ]); ?>
        </div>
      <?php else : ?>
        <div class="center">
          <h2><?php esc_html_e('Nothing published yet', 'appswifts'); ?></h2>
          <p><?php esc_html_e('Articles will appear here as they are published.', 'appswifts'); ?></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php
get_footer();
