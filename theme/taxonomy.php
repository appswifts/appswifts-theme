<?php
/**
 * Taxonomy archives for industry / service-provided / client-location.
 *
 * Each term gets its own indexable page, which is the SEO payoff of importing the
 * taxonomies: "tourism websites we have built" becomes a real URL that can rank,
 * the way the original site's tags hint at but never expose.
 */
get_header();

$term = get_queried_object();

$count = (int) ($term->count ?? 0);
?>
  <header class="page-hero">
    <div class="wrap">
      <h1>
        <?php
        printf(
            /* translators: 1: term name, 2: label e.g. "Service" */
            esc_html__('%1$s projects', 'appswifts'),
            esc_html($term->name ?? '')
        );
        ?>
      </h1>
      <p>
        <?php
        printf(
            /* translators: %d: number of projects */
            esc_html(_n('%d project built by AppSwifts.', '%d projects built by AppSwifts.', $count, 'appswifts')),
            $count
        );
        ?>
      </p>
      <p style="margin-top:var(--s-4)">
        <a href="<?php echo esc_url((string) get_post_type_archive_link('work')); ?>">
          &larr; <?php esc_html_e('All work', 'appswifts'); ?>
        </a>
      </p>
    </div>
  </header>

  <div class="section">
    <div class="wrap">
      <?php if (have_posts()) : ?>
        <div class="grid grid--3">
          <?php
          while (have_posts()) :
              the_post();
              echo appswifts_work_card(get_the_ID());
          endwhile;
          ?>
        </div>
        <div class="pagination"><?php echo paginate_links(['prev_text' => '&larr;', 'next_text' => '&rarr;']); ?></div>
      <?php else : ?>
        <div class="center"><h2><?php esc_html_e('Nothing here yet', 'appswifts'); ?></h2></div>
      <?php endif; ?>
    </div>
  </div>
<?php
get_footer();
