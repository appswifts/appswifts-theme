<?php
/**
 * Template Name: Pricing
 */
get_header();

$plans = [
    [
        'name'  => __('Starter Website', 'appswifts'),
        'price' => '250,000',
        'unit'  => __('one-off, from', 'appswifts'),
        'desc'  => __('For a business that needs to be findable and look like it means business.', 'appswifts'),
        'items' => [
            __('Up to 5 pages, designed for you', 'appswifts'),
            __('Mobile-first, fast, SEO-ready', 'appswifts'),
            __('Contact form and WhatsApp button', 'appswifts'),
            __('Google Analytics and Search Console set up', 'appswifts'),
            __('A year of hosting included', 'appswifts'),
        ],
        'cta'   => __('Start here', 'appswifts'),
        'feat'  => false,
    ],
    [
        'name'  => __('Growth', 'appswifts'),
        'price' => '650,000',
        'unit'  => __('one-off, from', 'appswifts'),
        'desc'  => __('For a business that wants the website bringing in customers, not just describing the company.', 'appswifts'),
        'items' => [
            __('Everything in Starter', 'appswifts'),
            __('10 to 15 pages, plus a blog set up for you', 'appswifts'),
            __('SEO foundation and schema markup', 'appswifts'),
            __('A booking or enquiry system', 'appswifts'),
            __('3 months of marketing support', 'appswifts'),
        ],
        'cta'   => __('Most popular', 'appswifts'),
        'feat'  => true,
    ],
    [
        'name'  => __('Swifts AI Workspace', 'appswifts'),
        'price' => '25,000',
        'unit'  => __('per month', 'appswifts'),
        'desc'  => __('Your own private AI workspace, doing the repetitive work every week.', 'appswifts'),
        'items' => [
            __('Private workspace, isolated from other clients', 'appswifts'),
            __('Content and email drafting', 'appswifts'),
            __('Customer reply suggestions', 'appswifts'),
            __('Document and data analysis', 'appswifts'),
            __('Cancel any time', 'appswifts'),
        ],
        'cta'   => __('Try it', 'appswifts'),
        'feat'  => false,
    ],
];
?>
  <header class="page-hero">
    <div class="wrap">
      <h1><?php esc_html_e('Clear prices, agreed before we start', 'appswifts'); ?></h1>
      <p><?php esc_html_e('You get a fixed quote up front. No hourly billing, and no invoice arriving with surprises on it.', 'appswifts'); ?></p>
    </div>
  </header>

  <div class="section">
    <div class="wrap">
      <div class="grid grid--3">
        <?php foreach ($plans as $p) : ?>
          <article class="card<?php echo $p['feat'] ? ' card--feature' : ''; ?>">
            <?php if ($p['feat']) : ?>
              <span class="badge" style="align-self:flex-start;margin-bottom:var(--s-3)"><?php esc_html_e('Recommended', 'appswifts'); ?></span>
            <?php endif; ?>
            <h3><?php echo esc_html($p['name']); ?></h3>
            <p class="small muted"><?php echo esc_html($p['desc']); ?></p>
            <p class="card__price">
              <?php echo esc_html($p['price']); ?><span class="card__price-unit"> RWF</span>
            </p>
            <p class="small muted" style="margin-bottom:var(--s-5)"><?php echo esc_html($p['unit']); ?></p>
            <ul class="stack small" style="list-style:none;padding:0;margin:0 0 var(--s-5);flex:1">
              <?php foreach ($p['items'] as $i) : ?>
                <li style="display:flex;gap:.6em;align-items:flex-start">
                  <span style="color:var(--brand-ink);flex:none;width:18px;height:18px;margin-top:.2em"><?php appswifts_icon('check'); ?></span>
                  <span><?php echo wp_kses_post($i); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
            <a class="btn <?php echo $p['feat'] ? 'btn--primary' : 'btn--ghost'; ?>" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php echo esc_html($p['cta']); ?></a>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="center" style="margin-top:var(--s-8)">
        <h2><?php esc_html_e('What comes with every plan', 'appswifts'); ?></h2>
        <p class="lead"><?php esc_html_e('SSL, daily backups and monitoring, whichever plan you pick. Plus a real person to call when something goes wrong.', 'appswifts'); ?></p>
        <div class="btn-row" style="justify-content:center">
          <a class="btn btn--ghost" href="<?php echo esc_url(home_url('/services/')); ?>"><?php esc_html_e('Compare the services in detail', 'appswifts'); ?></a>
          <a class="btn btn--ghost" href="https://wa.me/250781965789" rel="noopener"><?php esc_html_e('Ask us a question', 'appswifts'); ?></a>
        </div>
      </div>
    </div>
  </div>
<?php
get_footer();
