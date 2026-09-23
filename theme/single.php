<?php
/**
 * Single post / page.
 */
get_header();

while (have_posts()) :
    the_post();
    $is_post = is_singular('post');
?>
  <article>
    <header class="page-hero">
      <div class="wrap">
        <?php if ($is_post) : ?>
          <time class="post-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
            <?php echo esc_html(get_the_date()); ?>
          </time>
        <?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <?php if (has_excerpt()) : ?>
          <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <?php endif; ?>
      </div>
    </header>

    <?php if (has_post_thumbnail()) : ?>
      <div class="wrap" style="margin-top:calc(var(--s-6) * -1);position:relative;z-index:2">
        <?php the_post_thumbnail('large', ['style' => 'border-radius:var(--radius-lg);box-shadow:var(--shadow-lg)']); ?>
      </div>
    <?php endif; ?>

    <div class="section section--tight">
      <div class="wrap">
        <div class="entry-content">
          <?php the_content(); ?>
          <?php wp_link_pages(['before' => '<div class="pagination">', 'after' => '</div>']); ?>
        </div>

        <?php if ($is_post) : ?>
          <?php
          // Related posts by shared category. The migrated posts had no internal
          // links at all, which is why they never helped each other rank.
          $cats = wp_get_post_categories(get_the_ID());
          $more = $cats ? get_posts([
              'post_type'      => 'post',
              'posts_per_page' => 3,
              'post__not_in'   => [get_the_ID()],
              'category__in'   => $cats,
          ]) : [];
          if ($more) : ?>
            <div class="related">
              <h2><?php esc_html_e('Keep reading', 'appswifts'); ?></h2>
              <div class="grid grid--3">
                <?php foreach ($more as $m) : ?>
                  <article class="post-card">
                    <div class="post-card__body">
                      <time datetime="<?php echo esc_attr(get_the_date('c', $m)); ?>"><?php echo esc_html(get_the_date('', $m)); ?></time>
                      <h3><a href="<?php echo esc_url((string) get_permalink($m)); ?>"><?php echo esc_html(get_the_title($m)); ?></a></h3>
                      <p class="small muted"><?php echo esc_html(wp_trim_words((string) get_the_excerpt($m), 16)); ?></p>
                    </div>
                  </article>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <div style="margin-top:var(--s-7);padding-top:var(--s-5);border-top:1px solid var(--line)">
            <a class="btn btn--ghost" href="<?php echo esc_url(home_url('/blog/')); ?>">&larr; <?php esc_html_e('Back to all articles', 'appswifts'); ?></a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </article>
<?php
endwhile;

get_footer();
