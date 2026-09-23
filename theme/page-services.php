<?php
/**
 * Template Name: Services
 */
get_header();

/**
 * Each service block is a lead card plus a list of what it includes.
 *
 * It used to be four identical 3x3 grids of iconless cards, which is the classic
 * generated-page shape: the same component repeated twelve times so nothing has
 * a visual priority. Now each service leads with a card that carries its
 * heading, summary and a link, and the details sit beside it as a plain list
 * with hairline rules. Two shapes, and the eye knows where to start.
 */
$services = [
    [
        'id'    => 'web',
        'title' => 'Web design &amp; development',
        'lead'  => 'Your website has one job: turn visitors into enquiries. We build it for that, and for the speed Google rewards.',
        'items' => [
            ['Custom design',        'Designed around your brand and your customers. You will not find it on a template marketplace.'],
            ['Speed &amp; SEO',      'Core Web Vitals in the green, schema markup in place, and technical SEO sorted from the first day instead of bolted on later.'],
            ['E-commerce',           'Selling online with the payment methods Rwandan customers actually use, plus delivery zones and stock control.'],
        ],
        'link'  => ['/work/', 'See websites we have built'],
    ],
    [
        'id'    => 'apps',
        'title' => 'Apps, APIs &amp; automation',
        'lead'  => 'There is a point where a spreadsheet stops helping and starts costing you. That is when we build the system that replaces it.',
        'items' => [
            ['Booking systems', 'Reservations, deposits and availability taken without a staff member chasing messages all day.'],
            ['Dashboards',      'Your sales, stock and staff performance on one screen, updated as things happen.'],
            ['Integrations',    'Payments, WhatsApp, accounting and your CRM talking to each other, so nobody retypes the same order twice.'],
        ],
        'link'  => ['/work/industry/professional-services/', 'See systems we have built'],
    ],
    [
        'id'    => 'marketing',
        'title' => 'Digital marketing',
        'lead'  => 'Website traffic is not the goal. Paying customers are. We report on cost per enquiry, so you can see what your money bought.',
        'items' => [
            ['SEO',                 'Ranking for the phrases your customers type, whether they are in Kigali or searching from Germany.'],
            ['Ads',                 'Google and Meta campaigns reviewed every week, judged on enquiries rather than clicks.'],
            ['Content &amp; social', 'Posts and articles that make people trust you before they ever send a message.'],
        ],
        'link'  => ['/contact/', 'Ask for a marketing plan'],
    ],
    [
        'id'    => 'hosting',
        'title' => 'Hosting &amp; care',
        'lead'  => 'We keep your site online, patched and backed up, because a hack in the high season costs far more than the hosting does.',
        'items' => [
            ['Managed hosting',      'Monitored servers, SSL included, and email that lands in inboxes instead of spam folders.'],
            ['Backups &amp; security', 'Daily backups kept off-site, malware scanning, and updates applied before they become a problem.'],
            ['Support',              'Ongoing improvements, plus a phone number that reaches someone who knows your site.'],
        ],
        'link'  => ['/pricing/', 'See what it costs'],
    ],
];
?>

  <header class="page-hero">
    <div class="wrap">
      <span class="eyebrow"><?php esc_html_e('Services', 'appswifts'); ?></span>
      <h1><?php esc_html_e('Four services, built to bring you customers', 'appswifts'); ?></h1>
      <p><?php esc_html_e('They work together, but you do not have to start with all four. Pick the one that pays for itself first.', 'appswifts'); ?></p>
    </div>
  </header>

  <div class="section">
    <div class="wrap stack" style="gap:var(--s-8)">

      <?php foreach ($services as $s) : ?>
        <section id="<?php echo esc_attr($s['id']); ?>" class="service">
          <div class="service__lead">
            <h2><?php echo esc_html($s['title']); ?></h2>
            <p class="lead"><?php echo esc_html($s['lead']); ?></p>
            <p><a class="service__link" href="<?php echo esc_url(home_url($s['link'][0])); ?>">
              <?php echo esc_html($s['link'][1]); ?> &rarr;</a></p>
          </div>

          <ul class="service__items">
            <?php foreach ($s['items'] as [$name, $desc]) : ?>
              <li>
                <h3><?php echo esc_html($name); ?></h3>
                <p><?php echo esc_html($desc); ?></p>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endforeach; ?>

      <div class="cta">
        <h2><?php esc_html_e('Not sure which one you need?', 'appswifts'); ?></h2>
        <p><?php esc_html_e('Describe the problem and we will tell you the shortest route to fixing it, even when that means selling you less.', 'appswifts'); ?></p>
        <div class="btn-row">
          <a class="btn btn--dark btn--lg" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Get a quote', 'appswifts'); ?></a>
        </div>
      </div>

    </div>
  </div>
<?php
get_footer();
