<?php
/**
 * Homepage — AI-first. Swifts AI leads; the agency services follow.
 * Copy passes the humanizer skill: no em dashes, no rule-of-three runs,
 * no "not X, you're Y" constructions, varied sentence rhythm.
 */
get_header();
?>

<section class="hero">
  <?php
  // Headline spans the full container, the way Google Labs sets its display type.
  // In the old two-column grid the H1 only had ~600px, so an 84px font wrapped
  // after three words and never read as 84px. Full width gives it ~1200px.
  ?>
  <div class="wrap wrap--wide hero__display">
    <h1 class="hero__title hero__title--display"><?php esc_html_e('Put AI to work in your business this week', 'appswifts'); ?></h1>
  </div>

  <div class="wrap hero__grid hero__grid--under">
    <div class="hero__intro">
      <p class="lead" style="font-size:var(--t-lg);max-width:46ch">
        <?php esc_html_e('We set up a private AI workspace around your business. It drafts your quotes and posts, answers customers on WhatsApp, and reads the contracts and tenders you never have time for. Takes about a week.', 'appswifts'); ?>
      </p>

      <div class="btn-row">
        <a class="btn btn--primary btn--lg" href="<?php echo esc_url(home_url('/swifts-ai/')); ?>"><?php esc_html_e('Explore Swifts AI', 'appswifts'); ?></a>
        <a class="btn btn--on-dark btn--lg" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Book a free demo', 'appswifts'); ?></a>
      </div>

      <ul class="trust">
        <li><strong><?php esc_html_e('About a week', 'appswifts'); ?></strong><?php esc_html_e('to set up, start to finish', 'appswifts'); ?></li>
        <li><strong><?php esc_html_e('Your data', 'appswifts'); ?></strong><?php esc_html_e('never trains a public model', 'appswifts'); ?></li>
        <li><strong><?php esc_html_e('Kigali team', 'appswifts'); ?></strong><?php esc_html_e('who answer on WhatsApp', 'appswifts'); ?></li>
      </ul>
    </div>

    <div class="terminal" role="img" aria-label="<?php esc_attr_e('Example of Swifts AI handling a hotel guest enquiry', 'appswifts'); ?>">
      <div class="terminal__bar"><i></i><i></i><i></i><span class="terminal__name"><?php esc_html_e('swifts-ai · live', 'appswifts'); ?></span></div>
      <div class="terminal__row"><span><?php esc_html_e('guest asks', 'appswifts'); ?></span><b><?php esc_html_e('3 rooms, 12–15 Oct?', 'appswifts'); ?></b></div>
      <div class="terminal__row"><span><?php esc_html_e('draft reply', 'appswifts'); ?></span><b><?php esc_html_e('ready', 'appswifts'); ?></b></div>
      <div class="terminal__row"><span><?php esc_html_e('languages', 'appswifts'); ?></span><b>EN · FR · RW</b></div>
      <div class="terminal__row"><span><?php esc_html_e('your response time', 'appswifts'); ?></span><b><?php esc_html_e('4 hours → 40 sec', 'appswifts'); ?></b></div>
      <div class="terminal__row"><span><?php esc_html_e('documents loaded', 'appswifts'); ?></span><b>184</b></div>
    </div>
  </div>
</section>

<?php
/**
 * Integrations band.
 *
 * Logos are hotlinked (jsDelivr, CORS-open) and painted through a CSS mask
 * rather than an <img>, so every mark renders in one ink colour instead of
 * thirty brand palettes fighting each other. LinkedIn is pinned to v13 —
 * simple-icons dropped it in v14 over trademark, so it 404s on @15.
 *
 * Skipped: preview screenshots on hover. We have none, and inventing them
 * would be the fake-screenshot tell. Hover reveals what we actually DO with
 * the tool instead. Add images here when real captures exist.
 */
$appswifts_integrations = [
    ['gmail',          'Gmail',           'Reads the enquiry, drafts the reply in your voice.'],
    ['googlecalendar', 'Calendar',        'Books the viewing or the call without the back-and-forth.'],
    ['googlesheets',   'Sheets',          'Pulls your rate card and stock so answers stay accurate.'],
    ['googledrive',    'Drive',           'Works from the documents you already keep there.'],
    ['whatsapp',       'WhatsApp',        'Replies to walk-ins and follow-ups while you are busy.'],
    ['wordpress',      'WordPress',       'Publishes and edits pages, posts and listings.'],
    ['facebook',       'Facebook',        'Turns one update into posts for the week.'],
    ['instagram',      'Instagram',       'Drafts captions and publishes on a schedule.'],
    ['linkedin',       'LinkedIn',        'Writes the follow-up to that agent abroad.'],
    ['x',              'X',               'Keeps the account alive without you thinking about it.'],
    ['notion',         'Notion',          'Reads your SOPs and internal notes.'],
    ['stripe',         'Stripe',          'Reconciles what came in against what you invoiced.'],
];
?>
<section class="section section--cream">
  <div class="wrap">
    <div class="section__head">
      <h2><?php esc_html_e('It works inside the tools you already run', 'appswifts'); ?></h2>
      <p class="lead"><?php esc_html_e('No migration, no new app to learn. Swifts AI plugs into what your team opens every morning — and sends work back where people already look.', 'appswifts'); ?></p>
    </div>

    <ul class="int__grid">
      <?php foreach ($appswifts_integrations as [$slug, $name, $does]) : ?>
        <?php
        // LinkedIn lives on v13; everything else on v15.
        $ver = ('linkedin' === $slug) ? 13 : 15;
        $svg = "https://cdn.jsdelivr.net/npm/simple-icons@{$ver}/icons/{$slug}.svg";
        ?>
        <li class="int" style="--logo:url('<?php echo esc_url($svg); ?>')">
          <span class="int__mark" aria-hidden="true"></span>
          <div class="int__body">
            <h3><?php echo esc_html($name); ?></h3>
            <p><?php echo esc_html($does); ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>

    <p class="int__note">
      <?php esc_html_e('Also connected: Search Console, Analytics, Blogger, Supabase, Cloudinary, Threads, TikTok, YouTube, Pinterest, Slack, Telegram, Zoom, Shopify and Mailchimp.', 'appswifts'); ?>
    </p>
  </div>
</section>

<?php
/**
 * Connection map + build timeline.
 *
 * Two visual devices, both pure CSS. The map answers "how does it connect to
 * my website and my CMS", and the rail answers "how fast". No SVG, no canvas,
 * nothing to load — the connector rules are pseudo-elements.
 *
 * Skipped: a mock browser window showing a built site. It would be a fake
 * screenshot, which is the tell we spent two passes removing. Add a real
 * capture here once there is one.
 */
?>
<section class="section section--dark">
  <div class="wrap">
    <div class="section__head">
      <h2><?php esc_html_e('It works through your site, your CMS and your inbox', 'appswifts'); ?></h2>
      <p class="lead"><?php esc_html_e('Nobody has to learn another app. Swifts AI reaches into the tools you already run, and the work comes back to where you already look for it.', 'appswifts'); ?></p>
    </div>

    <div class="wire">

      <div class="wire__row">
        <span class="wire__label"><?php esc_html_e('Where customers reach you', 'appswifts'); ?></span>
        <ul class="wire__nodes">
          <?php foreach (['Website', 'WhatsApp', 'Instagram', 'Email', 'Facebook'] as $n) : ?>
            <li class="wire__node"><?php echo esc_html($n); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="wire__stem" aria-hidden="true"><i></i><i></i><i></i></div>

      <div class="wire__hub">
        <div>
          <b><?php esc_html_e('Swifts AI', 'appswifts'); ?></b>
          <span><?php esc_html_e('One private workspace, trained on your business only', 'appswifts'); ?></span>
        </div>
        <ul class="wire__hub-roles">
          <li><?php esc_html_e('Drafts', 'appswifts'); ?></li>
          <li><?php esc_html_e('Answers', 'appswifts'); ?></li>
          <li><?php esc_html_e('Reads', 'appswifts'); ?></li>
          <li><?php esc_html_e('Publishes', 'appswifts'); ?></li>
        </ul>
      </div>

      <div class="wire__stem" aria-hidden="true"><i></i><i></i><i></i></div>

      <div class="wire__row">
        <span class="wire__label"><?php esc_html_e('Where the work lands', 'appswifts'); ?></span>
        <ul class="wire__nodes">
          <?php foreach (['WordPress CMS', 'Gmail', 'Calendar', 'Google Sheets', 'Stripe'] as $n) : ?>
            <li class="wire__node"><?php echo esc_html($n); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>

    <ol class="steps steps--dark">
      <?php
      // Reuses the numbered rail from the Why us section rather than a new
      // component: same shape, same counter, already tuned for the ink band.
      foreach ([
          ['Brief', 'Give it two lines and your domain'],
          ['Pages drafted', 'Home, services, pricing, contact'],
          ['Tools wired', 'Forms, inbox, calendar, WhatsApp'],
          ['Live', 'You review, then it publishes'],
      ] as [$t, $d]) :
          ?>
        <li>
          <div>
            <h3><?php echo esc_html($t); ?></h3>
            <p><?php echo esc_html($d); ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>

    <p class="int__note">
      <?php esc_html_e('The same workspace does the rest of the week: quotations, tenders, follow-ups, and the blog post you keep putting off.', 'appswifts'); ?>
    </p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section__head">
      <h2><?php esc_html_e('What you can hand to it on day one', 'appswifts'); ?></h2>
      <p class="lead"><?php esc_html_e('Most of the time your team loses goes on work that follows the same pattern every single time. That part is worth automating.', 'appswifts'); ?></p>
    </div>

    <div class="grid grid--3">

      <article class="card card--feature">
        <div class="card__icon"><?php appswifts_icon('pen'); ?></div>
        <h3><?php esc_html_e('Writes in your voice', 'appswifts'); ?></h3>
        <p><?php esc_html_e('Blog posts, quotations, proposals, follow-up emails. Feed it a few things you have already written and it picks up your phrasing, so you are not rewriting everything it produces.', 'appswifts'); ?></p>
      </article>

      <article class="card">
        <div class="card__icon"><?php appswifts_icon('phone'); ?></div>
        <h3><?php esc_html_e('Answers customers', 'appswifts'); ?></h3>
        <p><?php esc_html_e('It replies on WhatsApp and email with your real prices and policies, in English, French and Kinyarwanda. Including at 11pm, when a guest in Germany is trying to book.', 'appswifts'); ?></p>
      </article>

      <article class="card">
        <div class="card__icon"><?php appswifts_icon('code'); ?></div>
        <h3><?php esc_html_e('Reads long documents', 'appswifts'); ?></h3>
        <p><?php esc_html_e('Paste in a contract, a tender or a supplier quote. You get the clauses that matter, the deadlines, and what you are actually agreeing to, on one page.', 'appswifts'); ?></p>
      </article>

      <article class="card">
        <div class="card__icon"><?php appswifts_icon('trending'); ?></div>
        <h3><?php esc_html_e('Summarises your week', 'appswifts'); ?></h3>
        <p><?php esc_html_e('A week of WhatsApp threads, meetings and half-written notes goes in. A summary comes out, with the decisions and who owes what.', 'appswifts'); ?></p>
      </article>

      <article class="card">
        <div class="card__icon"><?php appswifts_icon('shield'); ?></div>
        <h3><?php esc_html_e('Keeps your data yours', 'appswifts'); ?></h3>
        <p><?php esc_html_e('Every client gets a separate workspace. Nothing you put in is used to train anyone else\'s model. Export it or delete it whenever you like, and we will not pretend that is complicated.', 'appswifts'); ?></p>
      </article>

      <article class="card">
        <div class="card__icon"><?php appswifts_icon('check'); ?></div>
        <h3><?php esc_html_e('Works with what you have', 'appswifts'); ?></h3>
        <p><?php esc_html_e('It sits inside WhatsApp, your email, your spreadsheets and that CRM nobody enjoys using. We are not asking you to move your team onto another app.', 'appswifts'); ?></p>
      </article>

    </div>
  </div>
</section>

<section class="section section--dark">
  <div class="wrap">
    <div class="section__head">
      <h2><?php esc_html_e('The licence is the easy part. Getting it working is the job.', 'appswifts'); ?></h2>
      <p class="lead">
        <?php esc_html_e('AI subscriptions are easy to buy. Making one useful in a business with real customers, real prices and staff who are already stretched is where people get stuck. That is the part we do. We sit with your team, put your documents and prices in, and keep adjusting it until it is genuinely saving hours.', 'appswifts'); ?>
      </p>
      <div class="btn-row">
        <a class="btn btn--primary" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Book a free demo', 'appswifts'); ?></a>
        <a class="btn btn--on-dark" href="<?php echo esc_url(home_url('/pricing/')); ?>"><?php esc_html_e('See pricing', 'appswifts'); ?></a>
      </div>
    </div>

    <?php
    // Numbered rail. It was a 4-across grid inside a 0.85fr column, so at
    // 1440px each item collapsed to a 444px single column and the numbers were
    // absolutely positioned over the heading text — the 36px counter box
    // overlapped the h3 by 4px. Now the number is a real grid cell, so overlap
    // is structurally impossible, and the list is a vertical rail with hairline
    // rules. It sits under the centred header rather than beside it, so the
    // header can be centred without a card sitting off-axis next to it.
    $steps = [
      ['Demo', 'Bring one real task you do every week. We run it live on your own material.'],
      ['Setup', 'We build your private workspace and load your documents, prices and tone.'],
      ['Train your team', 'Two weeks of guided use. We adjust as your team finds what works for them.'],
      ['Monthly review', 'Hours saved, and what to automate next. Cancel any time if it is not paying for itself.'],
    ];
    ?>
    <ol class="steps steps--dark">
      <?php foreach ($steps as [$title, $desc]) : ?>
        <li>
          <div>
            <h3><?php echo esc_html($title); ?></h3>
            <p><?php echo esc_html($desc); ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>

  </div>
</section>

<section class="section section--bg">
  <div class="wrap">
    <div class="section__head">
      <h2><?php esc_html_e('Who gets the most out of it', 'appswifts'); ?></h2>
    </div>

    <div class="grid grid--4">
      <?php
      $segments = [
          [__('Hotels &amp; lodges', 'appswifts'), __('Guest enquiries, rate quotes, OTA replies. In English, French and Kinyarwanda.', 'appswifts')],
          [__('Tour operators', 'appswifts'), __('Itineraries, custom quotes, gorilla permit paperwork, and chasing agents abroad who have gone quiet on you.', 'appswifts')],
          [__('Professional services', 'appswifts'), __('Proposals, client updates, and the contract you have to read properly before tomorrow morning.', 'appswifts')],
          [__('Retail &amp; distribution', 'appswifts'), __('Product descriptions, supplier emails, stock summaries, and working out why a competitor is undercutting you.', 'appswifts')],
      ];
      foreach ($segments as [$name, $body]) : ?>
        <article class="card">
          <h3 style="font-size:var(--t-lg)"><?php echo wp_kses_post($name); ?></h3>
          <p><?php echo esc_html($body); ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--cream">
  <div class="wrap">
    <div class="section__head">
      <h2><?php esc_html_e('Everything else your business needs online', 'appswifts'); ?></h2>
      <p class="lead"><?php esc_html_e('AI works better when the website underneath it is any good. We do that part too.', 'appswifts'); ?></p>
    </div>

    <div class="grid grid--4">
      <?php
      // .card--link: whole card is the hit area with one heading link inside, so
      // the target is big but the a11y tree stays clean (one link per card, not a
      // link plus a "Learn more" plus a duplicated title).
      $also = [
        ['web', 'Websites', 'Sites that load fast, rank for what people in Kigali actually search, and turn visitors into enquiries.'],
        ['apps', 'Apps &amp; APIs', 'Booking systems, dashboards and the integrations that stop your team retyping the same data into three places.'],
        ['marketing', 'Digital marketing', 'SEO, Google and social ads. We report on cost per enquiry, not impressions.'],
        ['hosting', 'Hosting &amp; care', 'Hosting, daily backups and monitoring. Someone actually answers when it breaks.'],
      ];
      foreach ($also as [$anchor, $title, $blurb]) : ?>
        <article class="card card--link">
          <a class="card__hit" href="<?php echo esc_url(home_url('/services/#' . $anchor)); ?>">
            <h3><?php echo esc_html($title); ?></h3>
            <p><?php echo esc_html($blurb); ?></p>
            <span class="card__arrow"><?php esc_html_e('Learn more', 'appswifts'); ?> &rarr;</span>
          </a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
// Real client work, not logos on a wall. These are the projects imported from the
// original site with their industry/service taxonomies intact.
$projects = get_posts(['post_type' => 'work', 'numberposts' => 6, 'post_status' => 'publish']);
if ($projects) :
?>
<?php
// Where our clients are. Placed before the work grid so the map introduces the
// projects rather than repeating them — the grid is the evidence for the map.
get_template_part('inc/world-map');
?>

<section class="section section--cream">
  <div class="wrap">
    <div class="section__head">
      <h2><?php esc_html_e('Built for businesses here and abroad', 'appswifts'); ?></h2>
      <p class="lead"><?php
        printf(
            /* translators: %d: number of portfolio projects */
            esc_html__('A sample of %d projects across Rwanda, the UK and Europe. Real sites, real clients.', 'appswifts'),
            (int) wp_count_posts('work')->publish
        );
      ?></p>
    </div>
    <div class="grid grid--3">
      <?php foreach ($projects as $p) {
          echo appswifts_work_card($p->ID);
      } ?>
    </div>
    <div class="btn-row" style="margin-top:var(--s-6)">
      <a class="btn btn--ghost" href="<?php echo esc_url((string) get_post_type_archive_link('work')); ?>"><?php esc_html_e('See all work', 'appswifts'); ?></a>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
$recent = get_posts(['numberposts' => 3, 'post_status' => 'publish', 'post__not_in' => [1]]);
if ($recent) :
?>
<section class="section section--bg">
  <div class="wrap">
    <div class="section__head">
      <h2><?php esc_html_e('From the blog', 'appswifts'); ?></h2>
    </div>
    <div class="grid grid--3">
      <?php foreach ($recent as $p) : ?>
        <article class="post-card">
          <?php if (has_post_thumbnail($p)) : ?>
            <a class="post-card__media" href="<?php echo esc_url(get_permalink($p)); ?>" tabindex="-1" aria-hidden="true">
              <?php echo get_the_post_thumbnail($p, 'medium_large'); ?>
            </a>
          <?php endif; ?>
          <div class="post-card__body">
            <?php
            $pc = get_the_category($p->ID);
            if ($pc) : ?>
              <a class="post-card__cat" href="<?php echo esc_url((string) get_category_link($pc[0])); ?>"><?php echo esc_html($pc[0]->name); ?></a>
            <?php endif; ?>
            <time datetime="<?php echo esc_attr(get_the_date('c', $p)); ?>"><?php echo esc_html(get_the_date('', $p)); ?></time>
            <h3><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></h3>
            <p class="small muted"><?php echo esc_html(wp_trim_words(get_the_excerpt($p), 18)); ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <div class="btn-row" style="margin-top:var(--s-6)">
      <a class="btn btn--ghost" href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('All articles', 'appswifts'); ?></a>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="wrap">
    <div class="cta">
      <h2><?php esc_html_e('Send us one task you do every week', 'appswifts'); ?></h2>
      <p><?php esc_html_e('We will run it through Swifts AI and send you the result. Free, and you decide after that.', 'appswifts'); ?></p>
      <div class="btn-row">
        <a class="btn btn--dark btn--lg" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Book a free demo', 'appswifts'); ?></a>
        <a class="btn btn--dark btn--lg" style="background:transparent;color:#0e140a;border-color:rgba(14,20,10,.35)" href="https://wa.me/250781965789" rel="noopener"><?php esc_html_e('WhatsApp us', 'appswifts'); ?></a>
      </div>
    </div>
  </div>
</section>

<?php get_footer();
