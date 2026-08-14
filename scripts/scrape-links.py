#!/usr/bin/env python3
import json
import re
import urllib.request

URL = 'https://akishima-jichiren.jp/links/'


def strip_tags(text: str) -> str:
    return re.sub(r'<[^>]+>', '', text).strip()


def parse_items(html_chunk: str) -> list:
    items = []
    for li in re.finditer(r'<li[^>]*>(.*?)</li>', html_chunk, re.S):
        li_html = li.group(1)
        a = re.search(r'<a[^>]*href="([^"]*)"[^>]*>(.*?)</a>', li_html, re.S)
        if a:
            items.append({'label': strip_tags(a.group(2)), 'url': a.group(1)})
        else:
            text = strip_tags(li_html)
            if text:
                items.append({'label': text, 'url': ''})
    if not items:
        for a in re.finditer(r'<a[^>]*href="([^"]*)"[^>]*>(.*?)</a>', html_chunk, re.S):
            label = strip_tags(a.group(2))
            if label:
                items.append({'label': label, 'url': a.group(1)})
    return items


def main() -> None:
    html = urllib.request.urlopen(URL).read().decode('utf-8', 'ignore')
    m = re.search(r'class="entry-body[^"]*"[^>]*>(.*?)</div>\s*<div class="entry-footer', html, re.S)
    if not m:
        m = re.search(r'class="vk_post_body"[^>]*>(.*?)</div>\s*</div>\s*</article>', html, re.S)
    body = m.group(1) if m else html

    sections = []
    for h3_match in re.finditer(r'<h3[^>]*>(.*?)</h3>(.*?)(?=<h3[^>]*>|$)', body, re.S):
        title = strip_tags(h3_match.group(1))
        content = h3_match.group(2)
        section = {'title': title, 'groups': []}
        h4_parts = re.split(r'<h4[^>]*>(.*?)</h4>', content, flags=re.S)
        if len(h4_parts) <= 1:
            items = parse_items(content)
            if items:
                section['groups'].append({'title': '', 'items': items})
        else:
            i = 1
            while i < len(h4_parts):
                gtitle = strip_tags(h4_parts[i])
                gcontent = h4_parts[i + 1] if i + 1 < len(h4_parts) else ''
                items = parse_items(gcontent)
                if items:
                    section['groups'].append({'title': gtitle, 'items': items})
                i += 2
        if section['groups']:
            sections.append(section)

    print(json.dumps(sections, ensure_ascii=False, indent=2))
    print(f'// sections: {len(sections)}', file=__import__('sys').stderr)


if __name__ == '__main__':
    main()
