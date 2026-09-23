<?php
/**
 * Template Name: Services
 */
get_header();
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

      <section id="web">
        <h2><?php esc_html_e('Web design &amp; development', 'appswifts'); ?></h2>
        <p class="lead"><?php esc_html_e('Your website has one job: turn visitors into enquiries. We build it for that, and for the speed Google rewards.', 'appswifts'); ?></p>
        <div class="grid grid--3" style="margin-top:var(--s-5)">
          <div class="card"><h3><?php esc_html_e('Custom design', 'appswifts'); ?></h3><p><?php esc_html_e('Designed around your brand and your customers. You will not find it on a template marketplace.', 'appswifts'); ?></p></div>
          <div class="card"><h3><?php esc_html_e('Speed &amp; SEO', 'appswifts'); ?></h3><p><?php esc_html_e('Core Web Vitals in the green, schema markup in place, and technical SEO sorted from the first day instead of bolted on later.', 'appswifts'); ?></p></div>
          <div class="card"><h3><?php esc_html_e('E-commerce', 'appswifts'); ?></h3><p><?php esc_html_e('Selling online with the payment methods Rwandan customers actually use, plus delivery zones and stock control.', 'appswifts'); ?></p></div>
        </div>
      </section>

      <section id="apps">
        <h2><?php esc_html_e('Apps, APIs &amp; automation', 'appswifts'); ?></h2>
        <p class="lead"><?php esc_html_e('There is a point where a spreadsheet stops helping and starts costing you. That is when we build the system that replaces it.', 'appswifts'); ?></p>
        <div class="grid grid--3" style="margin-top:var(--s-5)">
          <div class="card"><h3><?php esc_html_e('Booking systems', 'appswifts'); ?></h3><p><?php esc_html_e('Reservations, deposits and availability taken without a staff member chasing messages all day.', 'appswifts'); ?></p></div>
          <div class="card"><h3><?php esc_html_e('Dashboards', 'appswifts'); ?></h3><p><?php esc_html_e('Your sales, stock and staff performance on one screen, updated as things happen.', 'appswifts'); ?></p></div>
          <div class="card"><h3><?php esc_html_e('Integrations', 'appswifts'); ?></h3><p><?php esc_html_e('Payments, WhatsApp, accounting and your CRM talking to each other, so nobody retypes the same order twice.', 'appswifts'); ?></p></div>
        </div>
      </section>

      <section id="marketing">
        <h2><?php esc_html_e('Digital marketing', 'appswifts'); ?></h2>
        <p class="lead"><?php esc_html_e('Website traffic is not the goal. Paying customers are. We report on cost per enquiry, so you can see what your money bought.', 'appswifts'); ?></p>
        <div class="grid grid--3" style="margin-top:var(--s-5)">
          <div class="card"><h3><?php esc_html_e('SEO', 'appswifts'); ?></h3><p><?php esc_html_e('Ranking for the phrases your customers type, whether they are in Kigali or searching from Germany.', 'appswifts'); ?></p></div>
          <div class="card"><h3><?php esc_html_e('Ads', 'appswifts'); ?></h3><p><?php esc_html_e('Google and Meta campaigns reviewed every week, judged on enquiries rather than clicks.', 'appswifts'); ?></p></div>
          <div class="card"><h3><?php esc_html_e('Content &amp; social', 'appswifts'); ?></h3><p><?php esc_html_e('Posts and articles that make people trust you before they ever send a message.', 'appswifts'); ?></p></div>
        </div>
      </section>

      <section id="hosting">
        <h2><?php esc_html_e('Hosting &amp; care', 'appswifts'); ?></h2>
        <p class="lead"><?php esc_html_e('We keep your site online, patched and backed up, because a hack in the high season costs far more than the hosting does.', 'appswifts'); ?></p>
        <div class="grid grid--3" style="margin-top:var(--s-5)">
          <div class="card"><h3><?php esc_html_e('Managed hosting', 'appswifts'); ?></h3><p><?php esc_html_e('Monitored servers, SSL included, and email that lands in inboxes instead of spam folders.', 'appswifts'); ?></p></div>
          <div class="card"><h3><?php esc_html_e('Backups &amp; security', 'appswifts'); ?></h3><p><?php esc_html_e('Daily backups kept off-site, malware scanning, and updates applied before they become a problem.', 'appswifts'); ?></p></div>
          <div class="card"><h3><?php esc_html_e('Support', 'appswifts'); ?></h3><p><?php esc_html_e('Ongoing improvements, plus a phone number that reaches someone who knows your site.', 'appswifts'); ?></p></div>
        </div>
      </section>

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
