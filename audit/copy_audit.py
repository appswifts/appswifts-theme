#!/usr/bin/env python3
"""Scan the theme's user-facing copy for the humanizer skill's AI tells.

Pattern numbers refer to the 34 patterns in the humanizer skill.
Checks the live site, so it audits what visitors actually read.
"""
import re
import sys
import urllib.request

PAGES = ["", "swifts-ai/", "services/", "pricing/", "work/", "contact/"]

# pattern -> (regex, human label)
CHECKS = [
    (7,  r"\b(delve|tapestry|testament|underscore|pivotal|crucial|vibrant|"
         r"showcase|fostering|garner|intricate|interplay|landscape|realm|"
         r"seamless|robust|leverage|elevate|empower|streamline|cutting-edge|"
         r"game-changer|deep dive|circle back|at the end of the day|"
         r"when it comes to|in a world where)\b", "AI vocabulary / clichés"),
    (8,  r"\b(serves as|stands as|functions as|boasts|represents a)\b", "copula avoidance"),
    (9,  r"\b(not (just|only|merely)\b[^.]{0,60}?\bbut\b|it'?s not about\b[^.]{0,60}?\bit'?s\b)",
         "negative parallelism"),
    (10, r"\b\w+, \w+,? and \w+\.", "possible rule-of-three (review)"),
    (12, r"\bfrom \w+ to \w+,? from \w+ to \w+\b", "false range"),
    (14, r"—|–", "em/en dash"),
    (17, r"^#{0,6}\s*(?:[A-Z][a-z']+\s+){2,}[A-Z][a-z']+", "title case heading (review)"),
    (23, r"\b(in order to|due to the fact that|at this point in time|"
         r"in the event that|has the ability to|it is important to note)\b", "filler phrase"),
    (24, r"\b(could potentially|might possibly|it could be argued|may or may not)\b", "excessive hedging"),
    (25, r"\b(the future looks bright|exciting times|journey toward excellence|"
         r"step in the right direction|poised for success)\b", "generic positive ending"),
    (27, r"\b(the real question is|at its core|in reality,|what really matters|"
         r"the deeper issue|the heart of the matter|fundamentally,)\b", "authority trope"),
    (28, r"\b(let'?s dive|let'?s explore|let'?s break|here'?s what you need to know|"
         r"without further ado|let'?s take a look)\b", "signposting"),
    (32, r"\b(ever wondered|what if we told you|think about it)\b", "rhetorical question"),
    (33, r"\b(interestingly,|importantly,|notably,|crucially,|essentially,|ultimately,|"
         r"additionally,|furthermore,|moreover,)\s", "sentence-opener tic"),
    (34, r"\b(and that'?s okay|and that'?s fine|there'?s nothing wrong with|"
         r"no shame in|you'?re not alone|it'?s completely normal)\b", "reassurance kicker"),
    (18, r"[\U0001F300-\U0001FAFF\u2600-\u27BF]", "emoji"),
    (19, r"[\u201C\u201D\u2018\u2019]", "curly quotes"),
    (20, r"\b(I hope this helps|let me know if|of course!|certainly!|"
         r"you'?re absolutely right|great question)\b", "chatbot artifact"),
]

def fetch(path):
    req = urllib.request.Request("https://new.appswifts.space/" + path,
                                 headers={"User-Agent": "curl/8.5.0"})
    return urllib.request.urlopen(req, timeout=30).read().decode("utf-8", "replace")

def visible(html):
    """Strip tags/script/style so we only scan copy a visitor can read."""
    html = re.sub(r"(?is)<(script|style|noscript|svg)[^>]*>.*?</\1>", " ", html)
    html = re.sub(r"(?s)<!--.*?-->", " ", html)
    html = re.sub(r"(?s)<[^>]+>", " ", html)
    html = (html.replace("&amp;", "&").replace("&#039;", "'").replace("&#8217;", "'")
                .replace("&nbsp;", " ").replace("&#8211;", "-").replace("&rarr;", "->")
                .replace("&quot;", '"').replace("&#8220;", '"').replace("&#8221;", '"'))
    return re.sub(r"\s+", " ", html).strip()

total = 0
fails = 0
for page in PAGES:
    try:
        text = visible(fetch(page))
    except Exception as e:                       # noqa: BLE001
        print(f"  SKIP  /{page}  ({e})")
        continue
    hits = []
    for num, rx, label in CHECKS:
        found = re.findall(rx, text, flags=re.IGNORECASE | re.MULTILINE)
        if found:
            sample = found[:3]
            hits.append(f"    pattern {num:<3} {label:<32} x{len(found)}  {sample}")
    name = "/" + page if page else "/ (home)"
    if hits:
        print(f"\n  {name}   [{len(text)} chars of copy]")
        print("\n".join(hits))
        fails += sum(1 for h in hits if "review" not in h)
    else:
        print(f"  CLEAN  {name}   [{len(text)} chars]")
    total += 1

print(f"\n  pages scanned: {total} | tell-groups found: {fails}")
sys.exit(0)
