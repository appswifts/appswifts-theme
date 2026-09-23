<?php
/**
 * Inline SVG icon set — replaces an icon plugin.
 * Stroke icons, currentColor, so they inherit the brand tint from the card.
 */
function appswifts_icon(string $name): void
{
    $paths = [
        'monitor'  => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'code'     => '<path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>',
        'trending' => '<path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
        'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
        'check'    => '<path d="M20 6 9 17l-5-5"/>',
        'arrow'    => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'phone'    => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/>',
        'mail'     => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/>',
        'pen'      => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'doc'      => '<path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7Z"/><path d="M14 2v5h5M9 13h6M9 17h4"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>',
        'pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
    ];

    // The "spark" cross-burst was the single most recognisable AI-brand glyph, so
    // it has been dropped from the set entirely rather than left unused. Unknown
    // names fall back to a neutral glyph.
    $d = $paths[$name] ?? $paths['check'];
    printf(
        '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">%s</svg>',
        $d // static, hard-coded markup above — safe by construction
    );
}
