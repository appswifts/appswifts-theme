<?php
/**
 * Header. Plain semantic markup — the mobile nav is pure CSS, no JS.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e('Skip to content', 'appswifts'); ?></a>

<header class="site-header">
  <div class="wrap site-header__inner">

    <?php
    // the_custom_logo() prints its own <a>. Do NOT wrap it in another anchor:
    // HTML parsers auto-close nested <a> tags, which ejects the <img> from
    // .site-brand and breaks the sizing rule.
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        printf(
            '<a class="site-brand" href="%s">%s</a>',
            esc_url(home_url('/')),
            esc_html((string) get_bloginfo('name'))
        );
    }
    ?>

    <?php
    // Plugin-free mobile nav: a visually-hidden checkbox drives a CSS-only
    // flyout via :checked. The real <nav> and its links stay in the DOM, so
    // keyboard, screen readers and no-CSS all still work.
    ?>
    <input type="checkbox" id="nav-toggle" class="nav-toggle-input screen-reader-text"
           aria-hidden="true" tabindex="-1">

    <label for="nav-toggle" class="nav-toggle">
      <span class="nav-toggle-open" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </span>
      <span class="nav-toggle-close" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
      </span>
      <span class="screen-reader-text"><?php esc_html_e('Menu', 'appswifts'); ?></span>
    </label>

    <nav class="site-nav" aria-label="<?php esc_attr_e('Primary', 'appswifts'); ?>">
      <?php
      // Everything inside one wrapper so the panel is a single grid child.
      // Two direct children would create a second, un-collapsible grid row.
      ?>
      <div class="site-nav__panel">
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'depth'          => 1,
                'items_wrap'     => '<ul>%3$s</ul>',
            ]);
        } else {
            appswifts_nav_fallback();
        }
        ?>
        <?php
        // The header CTA moves into the panel on mobile, so it is reachable
        // inside the same tap target rather than crowding the brand row.
        ?>
        <a class="btn btn--primary site-nav__cta" href="<?php echo esc_url(home_url('/contact/')); ?>">
          <?php esc_html_e('Start a project', 'appswifts'); ?>
        </a>
      </div>
    </nav>

    <a class="btn btn--primary site-header__cta" href="<?php echo esc_url(home_url('/contact/')); ?>">
      <?php esc_html_e('Start a project', 'appswifts'); ?>
    </a>

  </div>
</header>

<main id="main">
