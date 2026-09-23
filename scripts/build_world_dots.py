#!/usr/bin/env python3
"""Generate a dotted world-land SVG path — the dotted-map look, no npm.

Same projection the WordPress template uses for the arcs:
    x = (lng + 180) * 800/360
    y = (84 - lat)  * 330/140
so the mask and the arc layer overlay exactly.

Antarctica is dropped: it is one huge dot blob that no one needs on a marketing
map, and the original fades it out with a gradient mask anyway.
"""
import json
import pathlib
import urllib.request

SRC = ("https://raw.githubusercontent.com/nvkelso/natural-earth-vector/"
       "master/geojson/ne_110m_land.geojson")
LAT_TOP, LAT_BOT = 84.0, -56.0
W, H = 800, 330
STEP = 1.6          # degrees between dots
DOT = 2.3           # stroke-width in SVG units = dot diameter
OUT = pathlib.Path("/opt/data/scripts/appswifts-wp/theme/assets/world-dots.svg")


def proj(lat, lng):
    return ((lng + 180.0) * W / 360.0, (LAT_TOP - lat) * H / (LAT_TOP - LAT_BOT))


def load_rings(geom):
    """Return (outer_rings, hole_rings) as lists of [lng,lat] pairs."""
    t, c = geom["type"], geom["coordinates"]
    outers, holes = [], []
    polys = [c] if t == "Polygon" else c
    for poly in polys:
        outers.append(poly[0])
        holes.extend(poly[1:])
    return outers, holes


def pip(x, y, ring):
    """Ray casting. Degenerate edges at y1==y2 are skipped by the > test."""
    inside = False
    n = len(ring)
    for i in range(n):
        x1, y1 = ring[i - 1][0], ring[i - 1][1]
        x2, y2 = ring[i][0], ring[i][1]
        if (y1 > y) != (y2 > y):
            if x < (x2 - x1) * (y - y1) / (y2 - y1) + x1:
                inside = not inside
    return inside


def bbox(ring, pad=0.0):
    xs = [p[0] for p in ring]
    ys = [p[1] for p in ring]
    return (min(xs) - pad, min(ys) - pad, max(xs) + pad, max(ys) + pad)


def main():
    print("fetching land geometry ...")
    with urllib.request.urlopen(SRC, timeout=60) as r:
        gj = json.load(r)

    outers, holes = [], []
    for f in gj["features"]:
        o, h = load_rings(f["geometry"])
        outers.extend(o)
        holes.extend(h)
    ob = [bbox(r, 0.5) for r in outers]
    hb = [bbox(r, 0.5) for r in holes]
    print(f"{len(outers)} outer rings, {len(holes)} holes")

    dots = []
    lat = LAT_TOP
    while lat >= LAT_BOT:
        lng = -180.0
        while lng <= 180.0:
            hit = False
            for ring, (x0, y0, x1, y1) in zip(outers, ob):
                if lng < x0 or lng > x1 or lat < y0 or lat > y1:
                    continue
                if pip(lng, lat, ring):
                    hit = True
                    break
            if hit:
                for ring, (x0, y0, x1, y1) in zip(holes, hb):
                    if lng < x0 or lng > x1 or lat < y0 or lat > y1:
                        continue
                    if pip(lng, lat, ring):
                        hit = False
                        break
            if hit:
                x, y = proj(lat, lng)
                # h.01 + round linecap = a dot, and it is the smallest encoding
                dots.append(f"M{round(x)} {round(y)}h.01")
            lng += STEP
        lat -= STEP

    print(f"{len(dots)} dots")
    path = "".join(dots)
    svg = (
        f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W} {H}" '
        f'width="{W}" height="{H}" role="img" aria-label="World map">'
        f'<path d="{path}" fill="none" stroke="#fff" '
        f'stroke-width="{DOT}" stroke-linecap="round"/>'
        f"</svg>"
    )
    OUT.parent.mkdir(parents=True, exist_ok=True)
    OUT.write_text(svg)
    print(f"wrote {OUT} ({len(svg)/1024:.1f} KB)")


if __name__ == "__main__":
    main()
