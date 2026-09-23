<?php
/**
 * Portfolio importer — runs inside the container via `wp eval-file`.
 *
 * Idempotent: a project whose slug already exists is skipped, so re-running is
 * safe. Images are imported through WordPress itself (wp_insert_attachment +
 * wp_generate_attachment_metadata) so the attachment metadata and the image
 * sizes are built by core, not faked.
 *
 * Pass --dry-run as the LAST argv element to report without writing.
 */

// `wp eval-file` rejects unknown flags, so the mode comes in as an env var.
$dry = (bool) getenv('DRY_RUN');

$plan_file = '/tmp/import/portfolio-plan.json';
if (!is_readable($plan_file)) {
    WP_CLI::error("plan not found: {$plan_file}");
}
$plan = json_decode((string) file_get_contents($plan_file), true);
if (!is_array($plan)) {
    WP_CLI::error('plan is not valid JSON');
}

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$created = $skipped = $failed = $attached = $img_failed = 0;

// Seed the taxonomy vocabulary first. Under WP-CLI admin_init never fires, so
// without this every term lookup below fails and the import writes bare posts.
if (function_exists('appswifts_seed_terms')) {
    $n = appswifts_seed_terms();
    WP_CLI::log("  seeded {$n} taxonomy terms");
}

// --- dry run: validate everything, write nothing -------------------------
if ($dry) {
    $ready = 0;
    foreach ($plan as $p) {
        $exists = (bool) get_page_by_path($p['slug'], OBJECT, 'work');
        $files  = 0;
        foreach ($p['images'] as $f) {
            is_readable($f) ? $files++ : $img_failed++;
        }
        $terms_ok = true;
        foreach (['industry' => 'industries', 'service-provided' => 'services', 'client-location' => 'locations'] as $tax => $key) {
            foreach ($p[$key] as $t) {
                if (!term_exists($t, $tax)) {
                    $terms_ok = false;
                    WP_CLI::warning("    ! term not seeded: {$tax} / {$t}");
                }
            }
        }
        $flag = $exists ? 'EXISTS' : ($files === count($p['images']) && $terms_ok ? 'ready' : 'PROBLEM');
        if ($flag === 'ready') {
            $ready++;
        }
        WP_CLI::log(sprintf('  [%-7s] %-44s %d/%d img  %s',
            $flag, $p['slug'], $files, count($p['images']),
            implode(', ', array_slice($p['industries'] ?: ['-'], 0, 2))));
    }
    WP_CLI::success(sprintf(
        'DRY RUN — nothing written. %d of %d ready, %d already exist, %d unreadable images.',
        $ready, count($plan), 0, $img_failed
    ));
    return;
}

foreach ($plan as $p) {
    $existing = get_page_by_path($p['slug'], OBJECT, 'work');

    if ($existing) {
        $skipped++;
        WP_CLI::log("  = skip  {$p['slug']} (already a project, id {$existing->ID})");
        continue;
    }

    $post_id = wp_insert_post([
        'post_type'    => 'work',
        'post_status'  => 'publish',
        'post_title'   => $p['title'],
        'post_name'    => $p['slug'],
        'post_excerpt' => $p['excerpt'],
        'post_content' => '',        // original project pages had no prose
        'post_date'    => $p['date'] . ' +0000',
        'post_date_gmt' => get_gmt_from_date($p['date'] . ' +0000'),
    ], true);

    if (is_wp_error($post_id)) {
        $failed++;
        WP_CLI::warning("  ! post  {$p['slug']}: " . $post_id->get_error_message());
        continue;
    }

    foreach (['industry' => 'industries', 'service-provided' => 'services', 'client-location' => 'locations'] as $tax => $key) {
        if (!empty($p[$key])) {
            $r = wp_set_object_terms($post_id, $p[$key], $tax);
            if (is_wp_error($r)) {
                WP_CLI::warning("    ! term {$tax}: " . $r->get_error_message());
            }
        }
    }

    foreach ($p['images'] as $i => $file) {
        if (!is_readable($file)) {
            $img_failed++;
            WP_CLI::warning("    ! missing file {$file}");
            continue;
        }
        $att_id = media_handle_sideload(
            ['name' => basename($file), 'tmp_name' => $file],
            $post_id,
            $p['alt'][$i] ?? '',
        );
        if (is_wp_error($att_id)) {
            $img_failed++;
            WP_CLI::warning('    ! sideload ' . basename($file) . ': ' . $att_id->get_error_message());
            continue;
        }
        $attached++;
        if ($i === 0) {
            set_post_thumbnail($post_id, $att_id);
        }
    }

    $created++;
    WP_CLI::log(sprintf('  + %-44s id %-5d %d img', $p['slug'], $post_id, count($p['images'])));
}

WP_CLI::success(sprintf(
    '%screated %d, skipped %d, failed %d | %d images attached, %d image errors',
    $dry ? 'DRY RUN (nothing written) — ' : '',
    $created, $skipped, $failed, $attached, $img_failed
));
