<?php
/**
 * Posts page (the /blog/ hub). WordPress uses home.php for page_for_posts.
 */
get_header();
?>
  <header class="page-hero">
    <div class="wrap">
      <h1><?php esc_html_e('Practical guides for growing online in Africa', 'appswifts'); ?></h1>
      <p><?php esc_html_e('SEO, websites, digital marketing and AI. Written for business owners rather than developers.', 'appswifts'); ?></p>
    </div>
  </header>

  <div class="section">
    <div class="wrap">
      <?php
      // The original filed every post under one catch-all category, so the new ones
      // were re-categorised on import. Surface them: it is the only internal linking
      // the blog has, and it makes the hub navigable rather than a flat list.
      $cats = get_categories(['hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC']);
      if (count($cats) > 1) : ?>
        <nav class="filter" aria-label="<?php esc_attr_e('Filter by topic', 'appswifts'); ?>">
          <?php foreach ($cats as $c) : ?>
            <a class="pill" href="<?php echo esc_url((string) get_category_link($c)); ?>">
              <?php echo esc_html($c->name); ?><span class="pill__count"><?php echo (int) $c->count; ?></span>
            </a>
          <?php endforeach; ?>
        </nav>
      <?php endif; ?>

      <?php if (have_posts()) : ?>
        <div class="grid grid--3">
          <?php while (have_posts()) : the_post(); ?>
            <article class="post-card">
              <?php if (has_post_thumbnail()) : ?>
                <a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                  <?php the_post_thumbnail('medium_large'); ?>
                </a>
              <?php endif; ?>
              <div class="post-card__body">
                <?php
                $first = get_the_category();
                if ($first) : ?>
                  <a class="post-card__cat" href="<?php echo esc_url((string) get_category_link($first[0])); ?>">
                    <?php echo esc_html($first[0]->name); ?>
                  </a>
                <?php endif; ?>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="small muted"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
              </div>
            </article>
          <?php endwhile; ?>
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
