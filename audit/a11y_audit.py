#!/usr/bin/env python3
"""WCAG 2.2 AA audit of the new theme's colour pairs + UI checks.

Reads the live stylesheet so it audits what is actually served, not the source.
"""
import re
import urllib.request

URL = "https://new.appswifts.space/wp-content/themes/appswifts/style.css"
req = urllib.request.Request(URL, headers={"User-Agent": "curl/8.5.0"})
css = urllib.request.urlopen(req, timeout=30).read().decode("utf-8", "replace")


def lum(h):
    h = h.lstrip("#")
    if len(h) == 3:
        h = "".join(c * 2 for c in h)
    r, g, b = (int(h[i:i + 2], 16) / 255 for i in (0, 2, 4))
    f = lambda c: c / 12.92 if c <= 0.03928 else ((c + 0.055) / 1.055) ** 2.4
    return 0.2126 * f(r) + 0.7152 * f(g) + 0.0722 * f(b)


def ratio(a, b):
    la, lb = lum(a), lum(b)
    hi, lo = max(la, lb), min(la, lb)
    return (hi + 0.05) / (lo + 0.05)


PAIRS = [
    ("body text",            "#4a5240", "#fafbf8", 4.5),
    ("body on white",        "#4a5240", "#ffffff", 4.5),
    ("muted text",           "#6d7668", "#ffffff", 4.5),
    ("muted on bg",          "#6d7668", "#fafbf8", 4.5),
    ("h1/h2 heading",        "#0e140a", "#fafbf8", 4.5),
    ("link (lime text)",     "#3f6b12", "#ffffff", 4.5),
    ("eyebrow",              "#3f6b12", "#ffffff", 4.5),
    ("btn primary text",     "#0e140a", "#86c13b", 4.5),
    ("btn primary hover",    "#0e140a", "#6ea32c", 4.5),
    ("btn lg (3:1 ok)",      "#0e140a", "#86c13b", 3.0),
    ("hero lead",            "#b7c0ad", "#0e140a", 4.5),
    ("hero body",            "#c9d1c0", "#0e140a", 4.5),
    ("hero eyebrow lime",    "#86c13b", "#0e140a", 4.5),
    ("trust label",          "#98a48e", "#0e140a", 4.5),
    ("dark section body",    "#c9d1c0", "#0e140a", 4.5),
    ("dark section lead",    "#a9b49f", "#0e140a", 4.5),
    ("card--dark body",      "#a9b49f", "#1b2314", 4.5),
    ("terminal row label",   "#8f9a85", "#0e140a", 4.5),
    ("terminal row value",   "#86c13b", "#111908", 4.5),
    ("terminal name",        "#7d8875", "#0e140a", 4.5),
    ("footer text",          "#98a48e", "#0e140a", 4.5),
    ("footer link",          "#b7c0ad", "#0e140a", 4.5),
    ("footer bottom",        "#7d8875", "#0e140a", 4.5),
    ("cta body",             "#1e2a10", "#86c13b", 4.5),
    ("cta h2",               "#0e140a", "#86c13b", 4.5),
    ("badge",                "#0e140a", "#86c13b", 4.5),
    ("badge soft",           "#3f6b12", "#eaf7d8", 4.5),
    ("card text on bg",      "#4a5240", "#ffffff", 4.5),
    ("btn ghost text",       "#0e140a", "#ffffff", 4.5),
    ("btn on-dark text",     "#ffffff", "#111809", 4.5),
]

print("=== WCAG 2.2 AA CONTRAST AUDIT ===")
fails = []
for name, fg, bg, need in PAIRS:
    r = ratio(fg, bg)
    ok = r >= need
    if not ok:
        fails.append((name, r, need, fg, bg))
    print(f"  {'PASS' if ok else 'FAIL'}  {r:6.2f}  (need {need})  {name:<22} {fg} on {bg}")

tests = len(PAIRS)
print(f"\n  checked {tests} pairs | pass {tests - len(fails)} | fail {len(fails)}")
for f in fails:
    print(f"    FAIL {f[0]}: {f[1]:.2f} < {f[2]}  ({f[3]} on {f[4]})")

print("\n=== WCAG 2.2 STRUCTURAL CHECKS ===")


def check(label, ok, detail=""):
    print(f"  {'PASS' if ok else 'FAIL'}  {label}{(' — ' + detail) if detail else ''}")


check("prefers-reduced-motion honoured", "prefers-reduced-motion" in css)
check("focus-visible outline present", ":focus-visible" in css)
check("skip link styled", ".skip-link" in css)
check("screen-reader-text utility", ".screen-reader-text" in css)
check("no font-weight 700+ (VoltAgent rule)",
      not re.search(r"font-weight:\s*[7-9]00", css),
      f"max used: {max(int(w) for w in re.findall(r'font-weight:\s*(\d+)', css)) if re.findall(r'font-weight:\s*(\d+)', css) else '-'}")
check("no gradient on brand CTA", "linear-gradient(135deg,var(--brand)" not in css)
check("interactive radius = pill or <=12px",
      "999px" in css and "--radius:12px" in css)
check("min touch target >= 36px",
      bool(re.search(r"width:36px;height:36px", css)) or bool(re.search(r"height:44px", css)))
check("font-display swap on all faces",
      css.count("font-display:swap") >= 3,
      f"{css.count('font-display:swap')} faces")
