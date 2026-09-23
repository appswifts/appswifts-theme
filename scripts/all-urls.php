<?php
/**
 * Print every public URL on the site so the host can check them over HTTP.
 * Covers posts, pages, work, all three taxonomies and post categories.
 */
$urls = [];

$urls[] = home_url('/');
foreach (['post', 'page', 'work'] as $pt) {
    foreach (get_posts(['post_type' => $pt, 'posts_per_page' => -1, 'post_status' => 'publish']) as $p) {
        $urls[] = get_permalink($p);
    }
}
foreach (['industry', 'service-provided', 'client-location'] as $tax) {
    foreach ((array) get_terms(['taxonomy' => $tax, 'hide_empty' => true]) as $t) {
        if (!is_wp_error($t)) {
            $urls[] = get_term_link($t);
        }
    }
}
foreach (get_categories(['hide_empty' => true]) as $c) {
    $urls[] = get_category_link($c);
}

$seen = [];
foreach ($urls as $u) {
    if (is_wp_error($u) || !$u) {
        continue;
    }
    $path = wp_parse_url($u, PHP_URL_PATH);
    if ($path && !isset($seen[$path])) {
        $seen[$path] = 1;
        echo $path, "\n";
    }
}
