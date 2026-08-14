#!/bin/sh
# 担当者（編集者）を CSV から一括登録
# 本番サーバーで実行:
#   cd /home/akishimajichiren/www/wp3
#   sh /path/to/import-community-editors.sh assets/data/community-editors.csv
#
# CSV列:
#   slug, community_name, block, username, email, password, contact_name, notes
# email と password が両方空の行はスキップします。
# notes に skip と書いた行もスキップします。

set -eu

CSV="${1:-assets/data/community-editors.csv}"
BASE_URL="akishimajichiren.sakura.ne.jp/wp3"
WP_ROOT="${WP_ROOT:-/home/akishimajichiren/www/wp3}"
DUMMY_EMAIL_DOMAIN="${DUMMY_EMAIL_DOMAIN:-akishima-jichiren.local}"

if [ ! -f "$CSV" ]; then
    echo "ERROR: CSV not found: $CSV" >&2
    exit 1
fi

cd "$WP_ROOT"

python3 - "$CSV" "$BASE_URL" "$DUMMY_EMAIL_DOMAIN" <<'PY'
import csv
import os
import subprocess
import sys

csv_path, base_url, dummy_domain = sys.argv[1:4]
created = 0
updated = 0
skipped = 0
errors = 0

with open(csv_path, encoding="utf-8-sig", newline="") as handle:
    rows = list(csv.DictReader(handle))

for row in rows:
    slug = (row.get("slug") or "").strip()
    username = (row.get("username") or "").strip()
    email = (row.get("email") or "").strip()
    password = (row.get("password") or "").strip()
    notes = (row.get("notes") or "").strip().lower()
    community_name = (row.get("community_name") or "").strip()

    if not slug:
        skipped += 1
        continue
    if notes == "skip":
        print(f"SKIP (notes): {slug} {community_name}")
        skipped += 1
        continue
    if not email and not password:
        print(f"SKIP (empty email/password): {slug} {community_name}")
        skipped += 1
        continue
    if not username:
        username = f"editor-{slug}"
    if not email:
        email = f"{username}@{dummy_domain}"

    site_url = f"{base_url}/{slug}/"

    try:
        subprocess.run(
            ["wp", "site", "list", "--field=url"],
            check=True,
            capture_output=True,
            text=True,
        )
    except subprocess.CalledProcessError as exc:
        print(exc.stderr, file=sys.stderr)
        sys.exit(1)

    site_exists = False
    result = subprocess.run(
        ["wp", "site", "list", "--field=url"],
        check=True,
        capture_output=True,
        text=True,
    )
    for line in result.stdout.splitlines():
        if f"/{slug}/" in line:
            site_exists = True
            break

    if not site_exists:
        print(f"ERROR: site not found: {slug} ({community_name})")
        errors += 1
        continue

    user_id = subprocess.run(
        ["wp", "user", "get", username, "--field=ID"],
        capture_output=True,
        text=True,
    )

    if user_id.returncode != 0:
        if not password:
            print(f"ERROR: password required for new user: {username} ({slug})")
            errors += 1
            continue
        cmd = [
            "wp", "user", "create", username, email,
            "--role=editor",
            f"--user_pass={password}",
            f"--url={site_url}",
        ]
        if (row.get("contact_name") or "").strip():
            cmd.extend([
                f"--first_name={(row.get('contact_name') or '').strip()}",
            ])
        subprocess.run(cmd, check=True)
        print(f"CREATE: {slug} -> {username}")
        created += 1
        continue

    subprocess.run(
        ["wp", "user", "add-role", username, "editor", f"--url={site_url}"],
        check=True,
    )
    if password:
        subprocess.run(
            ["wp", "user", "update", username, f"--user_pass={password}"],
            check=True,
        )
    print(f"ADD ROLE: {slug} -> {username}")
    updated += 1

print("")
print(f"Done. created={created} updated={updated} skipped={skipped} errors={errors}")
if errors:
    sys.exit(1)
PY
