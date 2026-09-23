<?php
/**
 * Blog index — the /blog/ hub the current site is missing (it 404s today).
 */
get_header();
?>
  <header class="page-hero">
    <div class="wrap">
      <h1>
        <?php
        if (is_search()) {
            printf(esc_html__('Search: %s', 'appswifts'), esc_html(get_search_query()));
        } elseif (is_archive()) {
            the_archive_title();
        } else {
            esc_html_e('Practical guides for growing online in Africa', 'appswifts');
        }
        ?>
      </h1>
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
          <?php
          echo paginate_links([
              'prev_text' => '&larr; ' . esc_html__('Newer', 'appswifts'),
              'next_text' => esc_html__('Older', 'appswifts') . ' &rarr;',
          ]);
          ?>
        </div>

      <?php else : ?>
        <div class="center">
          <h2><?php esc_html_e('Nothing here yet', 'appswifts'); ?></h2>
          <p><?php esc_html_e('No articles match that request.', 'appswifts'); ?></p>
          <a class="btn btn--ghost" href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Back to the blog', 'appswifts'); ?></a>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php
get_footer();
