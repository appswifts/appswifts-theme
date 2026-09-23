#!/usr/bin/env python3
"""Check the header markup for the nested-anchor bug and confirm the logo is sized."""
import re
import urllib.request

req = urllib.request.Request(
    "https://new.appswifts.space/",
    headers={"User-Agent": "curl/8.5.0"},
)
html = urllib.request.urlopen(req, timeout=30).read().decode("utf-8", "replace")

m = re.search(r'<header class="site-header".*?</header>', html, re.S)
if not m:
    print("header not found")
    raise SystemExit(1)
seg = m.group(0)

nested = re.search(r'<a\b[^>]*>\s*<a\b', seg)
print("nested anchors :", "YES (BUG STILL PRESENT)" if nested else "no")

img = re.search(r'<img[^>]*custom-logo[^>]*>', seg)
print("logo img       :", img.group(0) if img else "NOT FOUND")

brand = re.search(r'class="site-brand"', seg)
print("site-brand span:", "present (text fallback)" if brand else "absent (custom logo active)")

opens, closes = seg.count("<a "), seg.count("</a>")
print(f"anchor balance : {opens} open / {closes} close")

# is the img inside the wrap container?
print("img in header  :", "yes" if img and img.start() < seg.find("</header>") else "no")

css = urllib.request.urlopen(
    urllib.request.Request(
        "https://new.appswifts.space/wp-content/themes/appswifts/style.css",
        headers={"User-Agent": "curl/8.5.0"},
    ),
    timeout=30,
).read().decode("utf-8", "replace")

rule = re.search(r'img\.custom-logo\{(.*?)\}', css, re.S)
print("css rule       :", " ".join(rule.group(1).split()) if rule else "MISSING")
