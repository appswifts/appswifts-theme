<?php
/**
 * Portfolio archive — /work/
 *
 * Replaces the hardcoded four-card Work page with the real thing: every project
 * imported from the original site, filterable by industry, service and location.
 *
 * The filters are plain links to the taxonomy archives, so they work with JS off,
 * are crawlable, and each one is a real indexable page.
 */
get_header();

$total = (int) wp_count_posts('work')->publish;

/**
 * Terms that actually have projects, so the filters never lead to an empty page.
 */
$facets = [];
foreach (['industry' => __('Industry', 'appswifts'), 'service-provided' => __('Service', 'appswifts'), 'client-location' => __('Location', 'appswifts')] as $tax => $label) {
    $terms = get_terms(['taxonomy' => $tax, 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC']);
    if (!is_wp_error($terms) && $terms) {
        $facets[$tax] = [$label, $terms];
    }
}
?>
  <header class="page-hero">
    <div class="wrap">
      <h1><?php esc_html_e('Things we built that are still running', 'appswifts'); ?></h1>
      <p>
        <?php
        printf(
            /* translators: %d: number of projects */
            esc_html(_n('%d project, and the sector we built it for.', '%d projects, and the sector we built each one for.', $total, 'appswifts')),
            $total
        );
        ?>
      </p>
    </div>
  </header>

  <div class="section">
    <div class="wrap">
      <?php if ($facets) : ?>
        <div class="filter-bar">
          <?php foreach ($facets as $tax => [$label, $terms]) : ?>
            <div class="filter-bar__row">
              <span class="filter-bar__label"><?php echo esc_html($label); ?></span>
              <ul class="pill-list">
                <?php foreach ($terms as $t) : ?>
                  <li>
                    <a class="pill" href="<?php echo esc_url((string) get_term_link($t)); ?>">
                      <?php echo esc_html($t->name); ?><span class="pill__n"><?php echo (int) $t->count; ?></span>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

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
        <div class="center">
          <h2><?php esc_html_e('No projects to show yet', 'appswifts'); ?></h2>
        </div>
      <?php endif; ?>

      <div class="cta" style="margin-top:var(--s-8)">
        <h2><?php esc_html_e('Want yours on this page?', 'appswifts'); ?></h2>
        <p><?php esc_html_e('Most of our work comes from people who saw a site we built and asked who made it. Tell us what you need.', 'appswifts'); ?></p>
        <div class="btn-row">
          <a class="btn btn--dark btn--lg" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Start a project', 'appswifts'); ?></a>
        </div>
      </div>
    </div>
  </div>
<?php
get_footer();
