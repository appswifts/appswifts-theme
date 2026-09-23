<?php
/**
 * World map — "where our clients are".
 *
 * A translation of the React/dotted-map component into this theme's terms. The
 * behaviour is kept, the runtime is not:
 *
 *   React original                     Here
 *   ---------------------------------  --------------------------------------
 *   `dotted-map` npm at runtime        Pre-generated SVG in assets/, built once
 *   framer-motion pathLength tween     CSS stroke-dashoffset (GPU, no JS)
 *   framer-motion offsetPath dot       SVG <animateMotion> along the same path
 *   next-themes light/dark switch      currentColor, inherits the band
 *   Tailwind aspect-[2/1]              aspect-ratio + one mobile override
 *
 * Consequence worth knowing: the arcs need no JavaScript at all, so the whole
 * section is one CSS file and one SVG file — no bundle, no hydration, and it
 * still animates with JS disabled.
 *
 * The dot field is generated from real Natural Earth land polygons, projected
 * with the SAME formula used for the arcs below:
 *     x = (lng + 180) * 800 / 360
 *     y = (84  - lat) * 330 / 140
 * If you change one, change the generator (scripts/build_world_dots.py) too, or
 * the arcs will drift off the land.
 *
 * Client coordinates come from the real `client-location` taxonomy, not a
 * hardcoded list — add a term and a marker appears.
 */

/** Kigali — the hub. Everything else is an arc from here. */
$aswp_hub = ['lat' => -1.9441, 'lng' => 30.0619, 'label' => 'Kigali'];

/** lat/lng per country term slug, used to place the destination markers. */
$aswp_coords = [
    'rwanda'         => ['lat' => -1.9441, 'lng' => 30.0619, 'label' => 'Kigali'],
    'united-kingdom' => ['lat' => 51.5074, 'lng' => -0.1278, 'label' => 'London'],
    'belgium'        => ['lat' => 50.8503, 'lng' => 4.3517,  'label' => 'Brussels'],
    'united-state'   => ['lat' => 40.7128, 'lng' => -74.0060, 'label' => 'New York'],
];

/**
 * Build the destination list from the taxonomy so it stays true as work grows.
 * Rwanda is the hub itself, so it never becomes an arc.
 */
$aswp_dests = [];
foreach (get_terms(['taxonomy' => 'client-location', 'hide_empty' => true]) as $aswp_term) {
    if ($aswp_term->slug === 'rwanda' || ! isset($aswp_coords[$aswp_term->slug])) {
        continue;
    }
    $aswp_dests[] = $aswp_coords[$aswp_term->slug]
        + ['count' => (int) $aswp_term->count, 'country' => $aswp_term->name];
}

if (empty($aswp_dests)) {
    return;
}

/** Project lat/lng into the 800x330 viewBox (see the header note). */
if (! function_exists('appswifts_map_point')) {
    function appswifts_map_point(float $lat, float $lng): array
    {
        return [($lng + 180.0) * 800.0 / 360.0, (84.0 - $lat) * 330.0 / 140.0];
    }
}

/** Quadratic arc, lifted above both endpoints. Same shape as the original. */
if (! function_exists('appswifts_map_arc')) {
    function appswifts_map_arc(array $a, array $b): string
    {
        [$x1, $y1] = appswifts_map_point($a['lat'], $a['lng']);
        [$x2, $y2] = appswifts_map_point($b['lat'], $b['lng']);
        $mx = ($x1 + $x2) / 2;
        $my = min($y1, $y2) - 46;
        return sprintf('M %.1f %.1f Q %.1f %.1f %.1f %.1f', $x1, $y1, $mx, $my, $x2, $y2);
    }
}

$aswp_arcs = [];
foreach ($aswp_dests as $aswp_d) {
    $aswp_arcs[] = ['d' => appswifts_map_arc($aswp_hub, $aswp_d), 'dest' => $aswp_d];
}
$aswp_ids = array_map(static fn($a, $i) => 'aswp-arc-' . $i, $aswp_arcs, array_keys($aswp_arcs));
?>
<section class="section section--bg map-section">
  <div class="wrap">

    <div class="section__head">
      <h2><?php esc_html_e('One team in Kigali. Clients across three continents.', 'appswifts'); ?></h2>
      <p class="lead"><?php
        printf(
            /* translators: 1: number of countries, 2: total project count */
            esc_html__('Live projects for clients in %1$d countries, %2$d delivered so far. Same people, same timezone for Rwanda — and we overlap the UK and European working day.', 'appswifts'),
            count($aswp_dests) + 1,
            (int) wp_count_posts('work')->publish
        );
      ?></p>
    </div>

    <div class="map" role="img"
         aria-label="<?php esc_attr_e('World map showing AppSwifts projects in Rwanda, the United Kingdom, Belgium and the United States', 'appswifts'); ?>">

      <?php
      // The dot field is an external file used as a CSS mask (see style.css),
      // not inlined markup: it stays cacheable and takes the band's ink colour.
      // Only the mask's alpha matters, so the file's white stroke is fine.
      ?>
      <span class="map__dots" aria-hidden="true"><span class="map__dots-in"></span></span>

      <svg class="map__svg" viewBox="0 0 800 330" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false">
        <defs>
          <?php /* Fade the arc in and out at both ends, as the gradient does. */ ?>
          <linearGradient id="aswp-arc-grad" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%"   stop-color="currentColor" stop-opacity="0"/>
            <stop offset="8%"   stop-color="currentColor" stop-opacity=".9"/>
            <stop offset="92%"  stop-color="currentColor" stop-opacity=".9"/>
            <stop offset="100%" stop-color="currentColor" stop-opacity="0"/>
          </linearGradient>
        </defs>

        <?php foreach ($aswp_arcs as $i => $aswp_arc) : ?>
          <path id="<?php echo esc_attr($aswp_ids[$i]); ?>"
                class="map__arc" d="<?php echo esc_attr($aswp_arc['d']); ?>"
                pathLength="1" stroke="url(#aswp-arc-grad)"
                style="animation-delay:<?php echo esc_attr(number_format($i * 0.55, 2, '.', '')); ?>s"/>
        <?php endforeach; ?>

        <?php
        // Markers. The hub gets a ring; destinations a plain dot with a slow
        // halo. Every marker carries its own <title>, so the map is readable
        // to a screen reader without duplicating the list beside it.
        [$hub_x, $hub_y] = appswifts_map_point($aswp_hub['lat'], $aswp_hub['lng']);
        ?>
        <circle class="map__hub-ring" cx="<?php echo esc_attr(round($hub_x, 1)); ?>"
                cy="<?php echo esc_attr(round($hub_y, 1)); ?>" r="5">
          <animate attributeName="r" values="5;17" dur="2.6s" repeatCount="indefinite"/>
          <animate attributeName="opacity" values=".55;0" dur="2.6s" repeatCount="indefinite"/>
        </circle>
        <circle class="map__hub" cx="<?php echo esc_attr(round($hub_x, 1)); ?>"
                cy="<?php echo esc_attr(round($hub_y, 1)); ?>" r="5.4">
          <title><?php esc_html_e('Kigali, Rwanda — AppSwifts', 'appswifts'); ?></title>
        </circle>

        <?php foreach ($aswp_arcs as $i => $aswp_arc) :
            [$dx, $dy] = appswifts_map_point($aswp_arc['dest']['lat'], $aswp_arc['dest']['lng']);
            $aswp_delay = number_format($i * 0.55, 2, '.', '');
        ?>
          <circle class="map__dest" cx="<?php echo esc_attr(round($dx, 1)); ?>"
                  cy="<?php echo esc_attr(round($dy, 1)); ?>" r="4.2">
            <title><?php echo esc_html(sprintf(
                /* translators: 1: city, 2: project count */
                _n('%1$s — %2$d project', '%1$s — %2$d projects', $aswp_arc['dest']['count'], 'appswifts'),
                $aswp_arc['dest']['label'],
                $aswp_arc['dest']['count']
            )); ?></title>
          </circle>
          <?php /* Travelling dot: SMIL along the very same path, zero JS. */ ?>
          <circle class="map__pulse" r="3.2">
            <animateMotion dur="3.4s" begin="<?php echo esc_attr($aswp_delay); ?>s"
                           repeatCount="indefinite" rotate="0" fill="freeze">
              <mpath href="#<?php echo esc_attr($aswp_ids[$i]); ?>"/>
            </animateMotion>
          </circle>
        <?php endforeach; ?>
      </svg>
    </div>

    <?php /* The same information as text — the map is decoration for this list. */ ?>
    <ul class="map__legend">
      <li class="map__legend-hub">
        <span class="map__dot map__dot--hub" aria-hidden="true"></span>
        <?php esc_html_e('Kigali, Rwanda — where we are', 'appswifts'); ?>
      </li>
      <?php foreach ($aswp_dests as $aswp_d) : ?>
        <li>
          <span class="map__dot" aria-hidden="true"></span>
          <?php echo esc_html(sprintf(
              /* translators: 1: city, 2: country, 3: project count */
              _n('%1$s, %2$s — %3$d project', '%1$s, %2$s — %3$d projects', $aswp_d['count'], 'appswifts'),
              $aswp_d['label'],
              $aswp_d['country'],
              $aswp_d['count']
          )); ?>
        </li>
      <?php endforeach; ?>
    </ul>

  </div>
</section>
