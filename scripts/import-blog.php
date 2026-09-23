<?php
/**
 * Blog importer — runs inside the container via `wp eval-file`.
 *
 * Idempotent by slug. The original posts were Elementor-built, so the REST
 * content is text only: no images are fabricated, the words are migrated as-is
 * and the categories are real ones rather than the original's single catch-all.
 */
if (!defined('WP_CLI')) {
    return;
}

$dry = (bool) getenv('DRY_RUN');

$plan_file = '/tmp/import/blog-plan.json';
if (!is_readable($plan_file)) {
    WP_CLI::error("plan not found: {$plan_file}");
}
$plan = json_decode((string) file_get_contents($plan_file), true);
if (!is_array($plan) || !$plan) {
    WP_CLI::error('plan is empty or invalid JSON');
}

WP_CLI::log('  plan: ' . count($plan) . ' posts');

$created = $skipped = $failed = 0;

if ($dry) {
    $ready = 0;
    foreach ($plan as $p) {
        $exists = (bool) get_page_by_path($p['slug'], OBJECT, 'post');
        $words  = str_word_count((string) $p['body']);
        if ($words < 60) {
            WP_CLI::warning("    ! too thin ({$words}w): {$p['slug']}");
            continue;
        }
        $exists ? $skipped++ : $ready++;
        WP_CLI::log(sprintf('  [%-6s] %5dw  %-58s %s',
            $exists ? 'EXISTS' : 'ready', $words, mb_substr($p['slug'], 0, 58),
            implode(', ', $p['categories'])));
    }
    WP_CLI::success(sprintf('DRY RUN — nothing written. %d ready, %d already exist.', $ready, $skipped));
    return;
}

foreach ($plan as $p) {
    $existing = get_page_by_path($p['slug'], OBJECT, 'post');
    if ($existing) {
        $skipped++;
        WP_CLI::log("  = {$p['slug']} (exists)");
        continue;
    }

    $id = wp_insert_post([
        'post_type'     => 'post',
        'post_status'   => 'publish',
        'post_title'    => $p['title'],
        'post_name'     => $p['slug'],
        'post_excerpt'  => $p['excerpt'],
        'post_content'  => $p['body'],
        'post_date'     => $p['date'],
        'post_date_gmt' => get_gmt_from_date($p['date']),
    ], true);

    if (is_wp_error($id)) {
        $failed++;
        WP_CLI::warning("  ! {$p['slug']}: " . $id->get_error_message());
        continue;
    }

    // Real categories, created on first use.
    $ids = [];
    foreach ((array) $p['categories'] as $name) {
        $term = term_exists($name, 'category');
        if (!$term) {
            $term = wp_insert_term($name, 'category');
        }
        if (!is_wp_error($term)) {
            $ids[] = (int) ($term['term_id'] ?? $term);
        }
    }
    if ($ids) {
        wp_set_post_categories($id, $ids);
    }

    $created++;
    WP_CLI::log(sprintf('  + %-52s id %-4d %dw  [%s]',
        mb_substr($p['slug'], 0, 52), $id, str_word_count($p['body']),
        implode(', ', $p['categories'])));
}

WP_CLI::success("created {$created}, skipped {$skipped}, failed {$failed}");
