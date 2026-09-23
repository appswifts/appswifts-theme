<?php
/**
 * Not found.
 */
get_header();
?>
  <div class="section" style="min-height:52vh;display:grid;place-items:center">
    <div class="wrap center">
      <h1><?php esc_html_e('That page has moved on', 'appswifts'); ?></h1>
      <p class="lead"><?php esc_html_e('The link you followed doesn\'t exist any more. Try one of these instead.', 'appswifts'); ?></p>
      <div class="btn-row" style="justify-content:center">
        <a class="btn btn--primary" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Homepage', 'appswifts'); ?></a>
        <a class="btn btn--ghost" href="<?php echo esc_url(home_url('/services/')); ?>"><?php esc_html_e('Services', 'appswifts'); ?></a>
        <a class="btn btn--ghost" href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'appswifts'); ?></a>
      </div>
    </div>
  </div>
<?php
get_footer();
