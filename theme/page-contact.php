<?php
/**
 * Template Name: Contact
 *
 * Contact page with the theme's own plugin-free form (admin-post handler +
 * nonce + honeypot lives in functions.php).
 */
get_header();
$sent  = isset($_GET['sent']);
$error = isset($_GET['error']);
?>
  <header class="page-hero">
    <div class="wrap">
      <span class="eyebrow"><?php esc_html_e('Get in touch', 'appswifts'); ?></span>
      <h1><?php esc_html_e('Tell us what you need', 'appswifts'); ?></h1>
      <p><?php esc_html_e('One working day for a reply, with a straight answer on scope, price and timeline.', 'appswifts'); ?></p>
    </div>
  </header>

  <div class="section">
    <div class="wrap">
      <div class="hero__grid">

        <div>
          <?php if ($sent) : ?>
            <div class="card card--feature">
              <div class="card__icon"><?php appswifts_icon('check'); ?></div>
              <h3><?php esc_html_e('Message sent', 'appswifts'); ?></h3>
              <p><?php esc_html_e('Thanks — we have it. You\'ll hear back within one working day.', 'appswifts'); ?></p>
            </div>
          <?php else : ?>
            <?php if ($error) : ?>
              <div class="card" style="border-color:#d9534f;margin-bottom:var(--s-5)">
                <p style="margin:0;color:#a33"><?php esc_html_e('Please add your name, a valid email and a message.', 'appswifts'); ?></p>
              </div>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="stack">
              <input type="hidden" name="action" value="appswifts_contact">
              <?php wp_nonce_field('appswifts_contact'); ?>
              <p style="position:absolute;left:-9999px" aria-hidden="true">
                <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
              </p>

              <p>
                <label for="name" class="small" style="font-weight:600;color:var(--ink)"><?php esc_html_e('Your name', 'appswifts'); ?></label>
                <input id="name" name="name" type="text" required
                       style="width:100%;padding:.7em .9em;border:1px solid var(--line);border-radius:var(--radius-sm);font:inherit;background:var(--surface)">
              </p>
              <p>
                <label for="email" class="small" style="font-weight:600;color:var(--ink)"><?php esc_html_e('Email', 'appswifts'); ?></label>
                <input id="email" name="email" type="email" required
                       style="width:100%;padding:.7em .9em;border:1px solid var(--line);border-radius:var(--radius-sm);font:inherit;background:var(--surface)">
              </p>
              <p>
                <label for="message" class="small" style="font-weight:600;color:var(--ink)"><?php esc_html_e('What do you need?', 'appswifts'); ?></label>
                <textarea id="message" name="message" rows="6" required
                          style="width:100%;padding:.7em .9em;border:1px solid var(--line);border-radius:var(--radius-sm);font:inherit;background:var(--surface)"></textarea>
              </p>

              <button class="btn btn--primary btn--lg" type="submit"><?php esc_html_e('Send message', 'appswifts'); ?></button>
            </form>
          <?php endif; ?>
        </div>

        <div class="stack">
          <div class="card">
            <div class="card__icon"><?php appswifts_icon('mail'); ?></div>
            <h3><?php esc_html_e('Email', 'appswifts'); ?></h3>
            <p><a href="mailto:appswifts@gmail.com">appswifts@gmail.com</a></p>
          </div>
          <div class="card">
            <div class="card__icon"><?php appswifts_icon('phone'); ?></div>
            <h3><?php esc_html_e('Phone &amp; WhatsApp', 'appswifts'); ?></h3>
            <p><a href="tel:+250781965789">+250 781 965 789</a><br>
               <a href="https://wa.me/250781965789" rel="noopener"><?php esc_html_e('Message on WhatsApp', 'appswifts'); ?></a></p>
          </div>
          <div class="card">
            <div class="card__icon"><?php appswifts_icon('pin'); ?></div>
            <h3><?php esc_html_e('Office', 'appswifts'); ?></h3>
            <p><?php esc_html_e('Kigali, Rwanda', 'appswifts'); ?></p>
          </div>
        </div>

      </div>
    </div>
  </div>
<?php
get_footer();
