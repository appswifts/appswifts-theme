<?php
/**
 * AppSwifts theme — plugin-free.
 *
 * Deliberately thin. The theme does three things:
 *   1. loads the one stylesheet and preloads the variable fonts
 *   2. declares modern WP supports (block styles, editor palette from tokens)
 *   3. exposes the design tokens to the block editor so content matches the theme
 *
 * No plugin is required for any of it.
 */

declare(strict_types=1);

const APPSWIFTS_VERSION = '1.0.0';

require_once get_stylesheet_directory() . '/inc/icons.php';
require_once get_stylesheet_directory() . '/inc/portfolio.php';

add_action('wp_enqueue_scripts', function (): void {
    $dir = get_stylesheet_directory();
    $uri = get_stylesheet_directory_uri();

    wp_enqueue_style('appswifts', get_stylesheet_uri(), [], (string) filemtime($dir . '/style.css'));

    // Design tokens also load in the block editor so the palette matches the front end.
    wp_add_inline_style('appswifts', ':root{--wp--preset--color--brand:#86c13b;--wp--preset--color--ink:#0e140a;}');
});

// Preload the three variable fonts: removes the invisible-text flash without a plugin.
add_action('wp_head', function (): void {
    $uri = get_stylesheet_directory_uri() . '/assets/fonts/';
    foreach (['dmsans-var.woff2', 'inter-var.woff2'] as $f) {
        printf(
            '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
            esc_url($uri . $f)
        );
    }
}, 1);

add_action('after_setup_theme', function (): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', ['height' => 68, 'width' => 240, 'flex-height' => true, 'flex-width' => true]);

    register_nav_menus([
        'primary' => __('Primary', 'appswifts'),
        'footer'  => __('Footer', 'appswifts'),
    ]);

    add_editor_style('style.css');
});

/**
 * Fallback nav so the header is never empty before a menu is assigned.
 */
function appswifts_nav_fallback(): void
{
    $items = [
        home_url('/swifts-ai/') => 'Swifts AI',
        home_url('/services/')  => 'Services',
        home_url('/pricing/')   => 'Pricing',
        home_url('/work/')      => 'Work',
        home_url('/blog/')      => 'Blog',
        home_url('/contact/')   => 'Contact',
    ];
    echo '<ul>';
    foreach ($items as $url => $label) {
        printf('<li><a href="%s">%s</a></li>', esc_url($url), esc_html($label));
    }
    echo '</ul>';
}

/**
 * The <title> for the current view.
 *
 * WordPress's own title-tag renders at wp_head priority 1, so printing our own
 * <title> would be ignored. Hook pre_get_document_title instead and let core
 * emit it. Also reused verbatim as og:title.
 */
function appswifts_seo_title(): string
{
    $site = (string) get_bloginfo('name');

    $t = match (true) {
        is_front_page()                  => $site,
        is_404()                         => 'Page not found · ' . $site,
        is_post_type_archive('work')     => 'Our work · websites, apps and brands we built · ' . $site,
        is_tax()                         => appswifts_tax_title(get_queried_object(), $site),
        is_category()                    => sprintf('%s guides and articles · %s', single_cat_title('', false), $site),
        is_home()                        => 'Guides on websites, SEO and AI for African businesses · ' . $site,
        is_singular('work')              => sprintf('%s · a project by AppSwifts', get_the_title()),
        is_singular()                    => sprintf('%s · %s', get_the_title(), $site),
        is_search()                      => sprintf('Search results for "%s" · %s', get_search_query(), $site),
        default                          => $site,
    };

    return trim(preg_replace('/\s+/', ' ', $t));
}

function appswifts_tax_title(?WP_Term $t, string $site): string
{
    if (!$t) {
        return $site;
    }

    return match ($t->taxonomy) {
        'client-location'  => sprintf('Our work for clients in %s · %s', $t->name, $site),
        'service-provided' => sprintf('%s projects we delivered · %s', $t->name, $site),
        default            => sprintf('Our work for %s clients · %s', $t->name, $site),
    };
}

add_filter('pre_get_document_title', 'appswifts_seo_title');

/**
 * Per-post SEO: description, Open Graph, Twitter card, JSON-LD.
 * Written by hand so no SEO plugin is needed.
 */
add_action('wp_head', function (): void {
    $queried = (int) get_queried_object_id();
    // Description: page excerpt -> page content -> site tagline.
    // A static front page has no excerpt/content, so the tagline must be the
    // fallback or the whole SEO block silently disappears.
    $desc = '';
    if (is_singular()) {
        $desc = get_the_excerpt() ?: wp_strip_all_tags((string) get_post_field('post_content', $queried));
    } elseif (is_category() || is_tag()) {
        // No term description on categories, so build one from the name. Without
        // this every category page would share the identical site tagline.
        $d = wp_strip_all_tags((string) term_description());
        $desc = $d !== '' ? $d : sprintf('%s articles by AppSwifts: practical guides on %s for business owners in Rwanda and across Africa.',
            single_cat_title('', false), strtolower(single_cat_title('', false)));
    } elseif (is_tax()) {
        // Term descriptions were seeded per term; without this every archive page
        // would carry the identical site tagline. Returned as HTML in block themes.
        $desc = wp_strip_all_tags((string) term_description());
    } elseif (is_post_type_archive('work')) {
        $n = (int) wp_count_posts('work')->publish;
        $desc = sprintf('%d websites, apps and brand projects AppSwifts built for clients in Rwanda, the UK and Europe. Filter by industry, service or country.', $n);
    } elseif (is_home()) {
        $desc = 'Practical guides on SEO, websites, AI and digital marketing, written for business owners in Rwanda and across Africa rather than for developers.';
    }
    if (trim($desc) === '') {
        $desc = (string) get_bloginfo('description');
    }
    $desc = trim(preg_replace('/\s+/', ' ', mb_substr($desc, 0, 158)));
    if ($desc === '') {
        $desc = 'Websites, apps, digital marketing and AI, built in Kigali, Rwanda for businesses across Africa.';
    }

    // Same string the <title> uses, so the share card always matches the page.
    $title = appswifts_seo_title();

    $url   = is_singular() ? (string) get_permalink() : home_url('/');
    $img   = is_singular() && has_post_thumbnail()
        ? (string) get_the_post_thumbnail_url(null, 'large')
        : '';
    // No featured image -> fall back to the site logo so every page still has
    // a valid share card.
    if ($img === '') {
        $logo_id = (int) get_theme_mod('custom_logo');
        $img = $logo_id ? (string) wp_get_attachment_image_url($logo_id, 'full') : '';
    }

    printf('<meta name="description" content="%s">' . "\n", esc_attr($desc));
    printf('<meta property="og:type" content="%s">' . "\n", is_singular() ? 'article' : 'website');
    printf('<meta property="og:title" content="%s">' . "\n", esc_attr($title));
    printf('<meta property="og:description" content="%s">' . "\n", esc_attr($desc));
    printf('<meta property="og:url" content="%s">' . "\n", esc_url($url));
    printf('<meta property="og:site_name" content="%s">' . "\n", esc_attr((string) get_bloginfo('name')));
    if ($img !== '') {
        printf('<meta property="og:image" content="%s">' . "\n", esc_url($img));
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

    // JSON-LD: Organization site-wide, Article on posts.
    $org = [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => 'AppSwifts',
        'url'      => home_url('/'),
        'email'    => 'appswifts@gmail.com',
        'address'  => ['@type' => 'PostalAddress', 'addressLocality' => 'Kigali', 'addressCountry' => 'RW'],
    ];
    if ($img !== '') {
        $org['logo'] = $img;
    }
    $graph = [$org];
    if (is_singular('post')) {
        $graph[] = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'author'        => ['@type' => 'Organization', 'name' => 'AppSwifts'],
        ];
    }
    printf(
        '<script type="application/ld+json">%s</script>' . "\n",
        wp_json_encode(count($graph) === 1 ? $graph[0] : ['@context' => 'https://schema.org', '@graph' => $graph])
    );
}, 5);

/**
 * Assets: cap image sizes, lazy-load, and drop emoji cruft (saves requests).
 */
add_action('init', function (): void {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_generator');
});
add_filter('wp_lazy_loading_enabled', fn() => true);

/**
 * Contact form handler for the theme's own form — no plugin.
 * Honeypot + nonce; sends through wp_mail.
 */
add_action('admin_post_nopriv_appswifts_contact', 'appswifts_handle_contact');
add_action('admin_post_appswifts_contact', 'appswifts_handle_contact');
function appswifts_handle_contact(): void
{
    check_admin_referer('appswifts_contact');

    if (($_POST['website'] ?? '') !== '') {           // honeypot
        wp_safe_redirect(home_url('/contact/?sent=1'));
        exit;
    }

    $name    = sanitize_text_field((string) ($_POST['name'] ?? ''));
    $email   = sanitize_email((string) ($_POST['email'] ?? ''));
    $message = sanitize_textarea_field((string) ($_POST['message'] ?? ''));

    if ($name === '' || !is_email($email) || $message === '') {
        wp_safe_redirect(home_url('/contact/?error=1'));
        exit;
    }

    wp_mail(
        'appswifts@gmail.com',
        sprintf('[appswifts.com] New enquiry from %s', $name),
        sprintf("Name: %s\nEmail: %s\n\n%s", $name, $email, $message),
        ['Reply-To: ' . $email]
    );

    wp_safe_redirect(home_url('/contact/?sent=1'));
    exit;
}

/**
 * Nothing else needed: no plugins, no page builder. The theme's own templates
 * plus the design tokens cover layout, SEO meta, icons and the contact form.
 */
