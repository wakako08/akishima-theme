#!/usr/bin/env python3
"""communities-data.php から担当者一括登録用 CSV を生成する。"""

import csv
import re
from pathlib import Path

THEME_DIR = Path(__file__).resolve().parents[1]
SRC = THEME_DIR / "inc" / "communities-data.php"
OUT = THEME_DIR / "assets" / "data" / "community-editors.csv"

KANJI = {
    1: "一", 2: "二", 3: "三", 4: "四", 5: "五", 6: "六", 7: "七", 8: "八", 9: "九", 10: "十",
    11: "十一", 12: "十二", 13: "十三", 14: "十四", 15: "十五", 16: "十六", 17: "十七",
    18: "十八", 19: "十九", 20: "二十", 21: "二十一",
}

FIELDS = [
    "slug",
    "community_name",
    "block",
    "username",
    "email",
    "password",
    "contact_name",
    "notes",
]


def community_slug(block_no: int, member_no: int) -> str:
    return f"{int(block_no):02d}-{int(member_no):02d}"


def parse_communities(src_text: str) -> list[dict]:
    rows = []
    current_block = None
    lines = src_text.splitlines()

    for i, line in enumerate(lines):
        if (
            re.search(r"'no'\s*=>\s*\d+,\s*$", line.strip())
            and i + 1 < len(lines)
            and "'name'    => '第" in lines[i + 1]
        ):
            match = re.search(r"'no'\s*=>\s*(\d+)", line)
            if match:
                current_block = int(match.group(1))
            continue

        member_match = re.search(
            r"array\(\s*'no'\s*=>\s*(\d+),\s*'name'\s*=>\s*'([^']+)'",
            line,
        )
        if member_match and current_block is not None:
            member_no = int(member_match.group(1))
            slug = community_slug(current_block, member_no)
            block_label = f"第{KANJI.get(current_block, current_block)}ブロック"
            rows.append(
                {
                    "slug": slug,
                    "community_name": member_match.group(2),
                    "block": block_label,
                    "username": f"editor-{slug}",
                    "email": "",
                    "password": "",
                    "contact_name": "",
                    "notes": "",
                }
            )

    return rows


def main() -> None:
    rows = parse_communities(SRC.read_text(encoding="utf-8"))
    OUT.parent.mkdir(parents=True, exist_ok=True)

    with OUT.open("w", encoding="utf-8-sig", newline="") as handle:
        writer = csv.DictWriter(handle, fieldnames=FIELDS)
        writer.writeheader()
        writer.writerows(rows)

    print(f"Wrote {len(rows)} rows to {OUT}")


if __name__ == "__main__":
    main()
