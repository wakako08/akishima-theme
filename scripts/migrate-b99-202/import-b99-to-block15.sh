#!/bin/bash
# 武蔵野会館 b99-202 → 第15ブロック お知らせ【1記事】
# SSH後:
#   cd ~/www/wp3
#   bash /path/to/import-b99-to-block15.sh
#
set -euo pipefail
WP="${WP:-wp}"
URL="${URL:-akishimajichiren.sakura.ne.jp/wp3/block-15/}"
DIR="$(cd "$(dirname "$0")" && pwd)"
FILE="$DIR/single-article.html"

echo "Import 1 article to: $URL"
$WP post create \
  --post_type=jichikai \
  --post_status=publish \
  --post_title='【武蔵野会館運営協議会】旧サイトコンテンツまとめ（b99-202より）' \
  --post_content="$(cat "$FILE")" \
  --url="$URL"

echo "Done."
$WP post list --post_type=jichikai --url="$URL" --fields=ID,post_title,post_date --posts_per_page=5
