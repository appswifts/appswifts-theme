<?php
/**
 * Template Name: Swifts AI
 * Copy humanized: no em dashes, no rule-of-three runs, plain verbs.
 */
get_header();

$features = [
    ['spark',    __('Knows your business', 'appswifts'), __('Give it your documents, prices and the way you actually talk. After that it writes and replies like someone who works there.', 'appswifts')],
    ['shield',   __('Private by design', 'appswifts'),   __('Each client gets an isolated workspace. Your data does not train anyone else\'s model. Export it or delete it whenever you want.', 'appswifts')],
    ['code',     __('Fits how you already work', 'appswifts'), __('It connects to WhatsApp, your email, your calendar and that CRM you keep meaning to tidy up. Your team stays where it is.', 'appswifts')],
    ['trending', __('Measured in results', 'appswifts'),  __('Hours saved, replies sent, documents processed. We send you the numbers every month so you can decide whether it is earning its keep.', 'appswifts')],
];

$uses = [
    __('Drafting quotes, proposals and follow-up emails', 'appswifts'),
    __('Answering the questions you get asked fifty times a week', 'appswifts'),
    __('Turning a voice note into a written summary', 'appswifts'),
    __('Writing blog posts and social captions in your voice', 'appswifts'),
    __('Reading a long contract for the clauses that matter', 'appswifts'),
    __('Translating between English, French and Kinyarwanda', 'appswifts'),
];
?>
  <header class="page-hero">
    <div class="wrap">
      <span class="eyebrow"><?php esc_html_e('Swifts AI', 'appswifts'); ?></span>
      <h1><?php esc_html_e('A private AI workspace, set up for your business', 'appswifts'); ?></h1>
      <p><?php esc_html_e('One tool that learns how your business works, handles the repetitive jobs, and keeps your data to yourself. Built and hosted by AppSwifts in Kigali.', 'appswifts'); ?></p>
      <div class="btn-row" style="margin-top:var(--s-5)">
        <a class="btn btn--primary btn--lg" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Request access', 'appswifts'); ?></a>
        <a class="btn btn--on-dark btn--lg" href="<?php echo esc_url(home_url('/pricing/')); ?>"><?php esc_html_e('See pricing', 'appswifts'); ?></a>
      </div>
    </div>
  </header>

  <div class="section">
    <div class="wrap">
      <div class="center" style="margin-bottom:var(--s-7)">
        <h2><?php esc_html_e('Where it beats a generic chatbot', 'appswifts'); ?></h2>
        <p class="lead"><?php esc_html_e('A free chatbot knows nothing about your prices, your rooms or your policies. Swifts AI is configured for your business by people you can phone.', 'appswifts'); ?></p>
      </div>
      <div class="grid grid--2">
        <?php foreach ($features as [$icon, $title, $body]) : ?>
          <article class="card">
            <div class="card__icon"><?php appswifts_icon($icon); ?></div>
            <h3><?php echo esc_html($title); ?></h3>
            <p><?php echo esc_html($body); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <section class="section section--bg">
    <div class="wrap">
      <div class="hero__grid">
        <div>
          <h2><?php esc_html_e('What clients hand over first', 'appswifts'); ?></h2>
          <ul class="stack" style="list-style:none;padding:0">
            <?php foreach ($uses as $u) : ?>
              <li style="display:flex;gap:.7em;align-items:flex-start">
                <span style="color:var(--brand-ink);flex:none;width:20px;height:20px;margin-top:.25em"><?php appswifts_icon('check'); ?></span>
                <span><?php echo esc_html($u); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="card card--dark" style="background:var(--ink);border-color:rgba(255,255,255,.1)">
          <h3 style="color:#fff"><?php esc_html_e('How onboarding works', 'appswifts'); ?></h3>
          <ol class="stack small" style="color:#a9b49f;padding-left:1.2em">
            <li><?php esc_html_e('A 30-minute call about how your business actually runs.', 'appswifts'); ?></li>
            <li><?php esc_html_e('We build the workspace and load your documents, prices and tone.', 'appswifts'); ?></li>
            <li><?php esc_html_e('Two weeks of guided use with your team, adjusting as we go.', 'appswifts'); ?></li>
            <li><?php esc_html_e('A monthly review of what is saving time and what to do next.', 'appswifts'); ?></li>
          </ol>
          <p class="small" style="color:#7d8875;margin-top:var(--s-5)">
            <?php esc_html_e('About a week of our time, start to finish.', 'appswifts'); ?>
          </p>
        </div>
      </div>
    </div>
  </section>

  <div class="section">
    <div class="wrap">
      <div class="cta">
        <h2><?php esc_html_e('Try it on something real', 'appswifts'); ?></h2>
        <p><?php esc_html_e('Send us one task you repeat every week. We will send back what Swifts AI makes of it, before you pay anything.', 'appswifts'); ?></p>
        <div class="btn-row">
          <a class="btn btn--dark btn--lg" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Request a demo', 'appswifts'); ?></a>
          <a class="btn btn--dark btn--lg" style="background:transparent;color:#0e140a;border-color:rgba(14,20,10,.35)" href="https://wa.me/250781965789" rel="noopener"><?php esc_html_e('WhatsApp us', 'appswifts'); ?></a>
        </div>
      </div>
    </div>
  </div>
<?php
get_footer();
