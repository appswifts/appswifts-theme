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
          <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                style="display:block;font-size:var(--t-xs);letter-spacing:.08em;text-transform:uppercase;color:var(--brand);margin-bottom:var(--s-3)">
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
