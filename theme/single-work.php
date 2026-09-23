<?php
/**
 * Single project.
 *
 * The original project pages were one line of text plus a gallery, which is why
 * they never ranked for anything. This keeps the real structure (title, tags,
 * gallery) and adds the JSON-LD CreativeWork so each project is machine-readable.
 */
get_header();
the_post();

$pid   = get_the_ID();
$terms = [];
foreach (['industry' => __('Industry', 'appswifts'), 'service-provided' => __('Services', 'appswifts'), 'client-location' => __('Client location', 'appswifts')] as $tax => $label) {
    $objs = wp_get_post_terms($pid, $tax);
    if (!is_wp_error($objs) && $objs) {
        $terms[$label] = [$tax, $objs];
    }
}

// Every image attached to this project, featured first.
$gallery = get_posts([
    'post_type'      => 'attachment',
    'post_parent'    => $pid,
    'post_mime_type' => 'image',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order ID',
    'order'          => 'ASC',
]);
$thumb_id = (int) get_post_thumbnail_id($pid);
usort($gallery, fn($a, $b) => ($b->ID === $thumb_id) <=> ($a->ID === $thumb_id));
?>
  <header class="page-hero">
    <div class="wrap">
      <h1><?php the_title(); ?></h1>
      <?php if (has_excerpt()) : ?>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
      <?php endif; ?>
      <p style="margin-top:var(--s-4)">
        <a href="<?php echo esc_url((string) get_post_type_archive_link('work')); ?>">
          &larr; <?php esc_html_e('All work', 'appswifts'); ?>
        </a>
      </p>
    </div>
  </header>

  <?php if ($gallery) : ?>
    <div class="section section--tight">
      <div class="wrap">
        <div class="gallery">
          <?php foreach ($gallery as $i => $att) : ?>
            <figure class="gallery__item<?php echo $i === 0 ? ' gallery__item--lead' : ''; ?>">
              <?php
              echo wp_get_attachment_image($att->ID, $i === 0 ? 'large' : 'medium_large', false, [
                  'loading' => $i === 0 ? 'eager' : 'lazy',
                  'alt'     => esc_attr(get_post_meta($att->ID, '_wp_attachment_image_alt', true) ?: get_the_title($pid)),
              ]);
              ?>
            </figure>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($terms) : ?>
    <div class="section section--tight">
      <div class="wrap">
        <dl class="detail-list">
          <?php foreach ($terms as $label => [$tax, $objs]) : ?>
            <div class="detail-list__row">
              <dt><?php echo esc_html($label); ?></dt>
              <dd>
                <?php
                $links = [];
                foreach ($objs as $t) {
                    $links[] = sprintf('<a href="%s">%s</a>', esc_url((string) get_term_link($t)), esc_html($t->name));
                }
                echo implode(', ', $links);
                ?>
              </dd>
            </div>
          <?php endforeach; ?>
        </dl>
      </div>
    </div>
  <?php endif; ?>

  <?php
  // Machine-readable project record. No plugin needed.
  // Note: $terms is string-keyed, so it must NOT be spread into array_merge --
  // PHP turns string keys into named arguments and fatals. Build the list plainly.
  $about = [];
  foreach ($terms as [$tax, $objs]) {
      foreach ($objs as $t) {
          $about[] = $t->name;
      }
  }

  $ld = [
      '@context'   => 'https://schema.org',
      '@type'      => 'CreativeWork',
      'name'       => get_the_title(),
      'url'        => get_permalink(),
      'creator'    => ['@type' => 'Organization', 'name' => 'AppSwifts', 'url' => home_url('/')],
      'about'      => $about,
      'dateCreated' => get_the_date('c'),
  ];
  if ($thumb_id) {
      $ld['image'] = (string) wp_get_attachment_image_url($thumb_id, 'large');
  }
  printf('<script type="application/ld+json">%s</script>', wp_json_encode($ld));

  // Vertical: the projects that share this project's industry.
  $industries = wp_get_post_terms($pid, 'industry', ['fields' => 'ids']);
  if (!is_wp_error($industries) && $industries) {
      $related = get_posts([
          'post_type'      => 'work',
          'posts_per_page' => 4,
          'post__not_in'   => [$pid],
          'tax_query'      => [['taxonomy' => 'industry', 'field' => 'term_id', 'terms' => $industries]],
      ]);
      if ($related) {
          echo '<div class="section section--tight"><div class="wrap"><h2>' . esc_html__('More in the same sector', 'appswifts') . '</h2><div class="grid grid--3">';
          foreach ($related as $r) {
              echo appswifts_work_card($r->ID);
          }
          echo '</div></div></div>';
      }
  }
  ?>
<?php
get_footer();
