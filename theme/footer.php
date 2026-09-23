</main><!-- #main -->

<footer class="site-footer">
  <div class="wrap">
    <div class="footer-grid">

      <div class="footer-brand">
        <a class="site-brand" href="<?php echo esc_url(home_url('/')); ?>" style="color:#fff;margin-bottom:1rem">
          <span><?php bloginfo('name'); ?></span>
        </a>
        <p><?php esc_html_e('Websites, apps, digital marketing and AI. Built in Kigali for businesses across Africa.', 'appswifts'); ?></p>
      </div>

      <div>
        <h4><?php esc_html_e('Services', 'appswifts'); ?></h4>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/services/#web')); ?>">Web design &amp; development</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/#apps')); ?>">Apps &amp; APIs</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/#marketing')); ?>">Digital marketing</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/#hosting')); ?>">Hosting &amp; care</a></li>
        </ul>
      </div>

      <div>
        <h4><?php esc_html_e('Products', 'appswifts'); ?></h4>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/swifts-ai/')); ?>">Swifts AI</a></li>
          <li><a href="https://menuforest.com" rel="noopener">MenuForest</a></li>
          <li><a href="<?php echo esc_url(home_url('/work/')); ?>">Our work</a></li>
          <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">Blog</a></li>
        </ul>
      </div>

      <div>
        <h4><?php esc_html_e('Contact', 'appswifts'); ?></h4>
        <ul>
          <li><a href="mailto:appswifts@gmail.com">appswifts@gmail.com</a></li>
          <li><a href="tel:+250781965789">+250 781 965 789</a></li>
          <li><?php esc_html_e('Kigali, Rwanda', 'appswifts'); ?></li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom">
      <span>&copy; <?php echo esc_html(date_i18n('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'appswifts'); ?></span>
      <?php
      if (has_nav_menu('footer')) {
          wp_nav_menu([
              'theme_location' => 'footer',
              'container'      => 'nav',
              'depth'          => 1,
              'items_wrap'     => '<ul>%3$s</ul>',
          ]);
      }
      ?>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
