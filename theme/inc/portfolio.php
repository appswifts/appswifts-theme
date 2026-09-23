<?php
/**
 * Portfolio: the `work` CPT + its three taxonomies, ported from the original site.
 *
 * Same slugs as appswifts.com so the archive mirror, the taxonomy term lists and
 * any inbound links keep working. Registered in the theme because the site is
 * plugin-free: a CPT in functions.php is native WP, no plugin needed.
 *
 * show_in_rest = true is what lets the block editor and the WP REST API see it.
 */

declare(strict_types=1);

add_action('init', function (): void {
    register_post_type('work', [
        'labels' => [
            'name'          => __('Work', 'appswifts'),
            'singular_name' => __('Project', 'appswifts'),
            'add_new_item'  => __('Add project', 'appswifts'),
            'edit_item'     => __('Edit project', 'appswifts'),
        ],
        'public'       => true,
        'has_archive'  => 'work',
        'menu_icon'    => 'dashicons-portfolio',
        'menu_position' => 21,
        'rewrite'      => ['slug' => 'work', 'with_front' => false],
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes'],
        'show_in_rest' => true,
        'rest_base'    => 'work',
        'taxonomies'   => ['industry', 'service-provided', 'client-location'],
    ]);

    // The three public taxonomies share a shape, so build them from one list.
    $taxes = [
        'industry'         => ['Industry', 'Industries', 'industry'],
        'service-provided' => ['Service', 'Services', 'service-provided'],
        'client-location'  => ['Client location', 'Client locations', 'client-location'],
    ];

    foreach ($taxes as $slug => [$single, $plural, $rewrite]) {
        register_taxonomy($slug, ['work'], [
            'labels' => [
                'name'          => __($plural, 'appswifts'),
                'singular_name' => __($single, 'appswifts'),
            ],
            'public'            => true,
            'hierarchical'      => true,          // checkbox UI, like the original
            'show_in_rest'      => true,
            'rest_base'         => $slug,
            'rewrite'           => ['slug' => $rewrite, 'with_front' => false],
            'show_admin_column' => true,
        ]);
    }
});

/**
 * Seed the term lists once, straight from the original site, so the editor has
 * the real vocabulary instead of an empty box.
 *
 * Named (not a closure) so WP-CLI can call it too: admin_init never fires under
 * the CLI, so a CLI import must seed the terms itself or every term is missing.
 */
function appswifts_seed_terms(bool $force = false): int
{
    if (!$force && get_option('appswifts_terms_seeded')) {
        return 0;
    }

    $seed = [
        'industry' => [
            'Agriculture & Green Solutions', 'Automotive', 'Construction', 'E-commerce & Retail',
            'Education', 'Events', 'Fashion & Beauty', 'Healthcare & Medical', 'Hospitality',
            'Infrastructure&Technology', 'Manufacturing', 'NGOs & Nonprofits', 'Professional Services',
            'Real Estate', 'Restaurant', 'Startups & Tech', 'Tourism & Travel',
        ],
        'service-provided' => [
            'AI & Automation', 'Animation', 'Booking & Reservation Systems', 'Branding & Design',
            'Business Management Systems', 'Content Marketing', 'Custom CRM Tools',
            'Digital Marketing', 'Google Analytics Setup', 'Landing Pages', 'Lead Generation Tools',
            'Logo Design', 'On-Page SEO', 'Responsive Design (Mobile First)', 'SEO & Analytics',
            'Social Media Kit', 'Social Media Marketing', 'Software Solutions',
            'Web Application (Custom)', 'Website Development', 'WordPress Development',
        ],
        'client-location' => [
            'Rwanda', 'United Kingdom', 'Belgium', 'United State',
        ],
    ];

    $added = 0;
    foreach ($seed as $tax => $terms) {
        if (!taxonomy_exists($tax)) {
            continue;
        }
        foreach ($terms as $t) {
            $existing = term_exists($t, $tax);
            if (!$existing) {
                $r = wp_insert_term($t, $tax);
                if (!is_wp_error($r)) {
                    $added++;
                    $existing = $r;
                }
            }
            // A term description is the meta description for that archive page.
            // Without one, all 44 taxonomy pages would share the site tagline.
            if ($existing && !is_wp_error($existing)) {
                $id = (int) ($existing['term_id'] ?? $existing);
                $term = get_term($id, $tax);
                if ($term && !is_wp_error($term) && $term->description === '') {
                    wp_update_term($id, $tax, ['description' => appswifts_term_description($t, $tax)]);
                }
            }
        }
    }

    update_option('appswifts_terms_seeded', 1);

    return $added;
}

/**
 * One plain sentence per term, unique across all three taxonomies.
 * ponytail: template text; edit any term in wp-admin to override it.
 */
function appswifts_term_description(string $name, string $tax): string
{
    $core = match ($tax) {
        'industry'         => sprintf('%s projects built by AppSwifts', $name),
        'service-provided' => sprintf('%s delivered by AppSwifts', $name),
        default            => sprintf('Projects AppSwifts delivered for clients in %s', $name),
    };

    return $core . '. Websites, apps, branding and digital marketing, made in Kigali, Rwanda and used across Africa, the UK and Europe.';
}

add_action('admin_init', fn() => appswifts_seed_terms());

/**
 * 22 projects in one flat grid beats three pages of pagination, and the sector
 * filters need the whole set on screen to be worth clicking. Same for the blog:
 * 18 posts all linked from the hub is better for crawling than 2 paginated pages.
 * ponytail: past ~60 items, switch to paginate_links() and keep filters as query args.
 */
add_action('pre_get_posts', function (WP_Query $q): void {
    if (is_admin() || !$q->is_main_query()) {
        return;
    }
    if ($q->is_post_type_archive('work')
        || $q->is_tax(['industry', 'service-provided', 'client-location'])
        || $q->is_home()) {
        $q->set('posts_per_page', -1);
    }
});

/**
 * Portfolio archive + taxonomy pages need their own cards, so give the theme a
 * single place that renders one project.
 */
function appswifts_work_card(int $post_id): string
{
    $terms = [];
    foreach (['industry', 'service-provided', 'client-location'] as $tax) {
        $names = wp_get_post_terms($post_id, $tax, ['fields' => 'names']);
        if (!is_wp_error($names)) {
            $terms = array_merge($terms, $names);
        }
    }
    $terms = array_slice(array_unique($terms), 0, 3);

    $img = get_the_post_thumbnail($post_id, 'medium_large', [
        'loading' => 'lazy',
        'alt'     => esc_attr(get_the_title($post_id) . ' - project by AppSwifts'),
    ]);

    ob_start(); ?>
    <article class="card work-card">
      <?php if ($img) : ?>
        <div class="work-card__media"><?php echo $img; ?></div>
      <?php endif; ?>
      <div class="work-card__body">
        <h3><a href="<?php echo esc_url((string) get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a></h3>
        <?php if ($terms) : ?>
          <p class="work-card__tags"><?php
              foreach ($terms as $t) {
                  printf('<span class="badge badge--soft">%s</span>', esc_html($t));
              }
          ?></p>
        <?php endif; ?>
        <p style="flex:1"><?php echo esc_html(wp_trim_words((string) get_the_excerpt($post_id), 24)); ?></p>
      </div>
    </article>
    <?php
    return (string) ob_get_clean();
}
