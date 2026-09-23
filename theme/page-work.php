<?php
/**
 * Template Name: Work
 */
get_header();

$work = [
    [
        'name'  => 'Swifts AI',
        'tag'   => __('SaaS platform', 'appswifts'),
        'desc'  => __('A multi-tenant AI workspace we built and run: a separate runtime per client, model routing with automatic fallback, token metering and billing. It has been in daily use since 2024.', 'appswifts'),
        'url'   => home_url('/swifts-ai/'),
        'link'  => __('Read more', 'appswifts'),
    ],
    [
        'name'  => 'MenuForest',
        'tag'   => __('Web app + QR', 'appswifts'),
        'desc'  => __('QR menus for hotels, restaurants and bars. The owner changes a price and it is live in seconds. Guests scan the code on the table and read it, no app to download and nothing to reprint.', 'appswifts'),
        'url'   => 'https://menuforest.com',
        'link'  => __('Visit site', 'appswifts'),
    ],
    [
        'name'  => 'Gorilla Brothers Safaris',
        'tag'   => __('Tourism · website', 'appswifts'),
        'desc'  => __('A booking-focused site for a gorilla trekking operator in the Virunga Massif. Tour engine, enquiry capture and content in several languages.', 'appswifts'),
        'url'   => 'https://gorilla-brothers.com',
        'link'  => __('Visit site', 'appswifts'),
    ],
    [
        'name'  => 'AppSwifts managed hosting',
        'tag'   => __('Infrastructure', 'appswifts'),
        'desc'  => __('Sites and email for more than 95 clients, on infrastructure we monitor ourselves. Backups go off-site nightly, SSL renews without anyone remembering to do it, and something tells us when a site goes down.', 'appswifts'),
        'url'   => home_url('/services/#hosting'),
        'link'  => __('Read more', 'appswifts'),
    ],
];
?>
  <header class="page-hero">
    <div class="wrap">
      <span class="eyebrow"><?php esc_html_e('Our work', 'appswifts'); ?></span>
      <h1><?php esc_html_e('Things we built that are still running', 'appswifts'); ?></h1>
      <p><?php esc_html_e('Products we own, and platforms we look after for clients in Rwanda and beyond.', 'appswifts'); ?></p>
    </div>
  </header>

  <div class="section">
    <div class="wrap">
      <div class="grid grid--2">
        <?php foreach ($work as $w) : ?>
          <article class="card">
            <span class="badge badge--soft" style="align-self:flex-start;margin-bottom:var(--s-3)"><?php echo esc_html($w['tag']); ?></span>
            <h3><?php echo esc_html($w['name']); ?></h3>
            <p style="flex:1"><?php echo esc_html($w['desc']); ?></p>
            <p style="margin:var(--s-4) 0 0">
              <a href="<?php echo esc_url($w['url']); ?>"<?php echo str_starts_with($w['url'], home_url()) ? '' : ' rel="noopener"'; ?>>
                <?php echo esc_html($w['link']); ?> &rarr;
              </a>
            </p>
          </article>
        <?php endforeach; ?>
      </div>

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
