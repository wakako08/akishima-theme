#!/bin/sh
# ブロック子サイト（block-01 〜 block-21）を一括作成
# 自治会作成スクリプトと同方式。
#
# 使い方（本番サーバー）:
#   1. このファイルを /home/akishimajichiren/www/wp3/ にアップロード
#   2. cd /home/akishimajichiren/www/wp3
#   3. sh create-block-sites.sh
#
# ※ テーマ側の block.php / communities-routing.php 等を先に反映してから実行してください。

set -e

cd /home/akishimajichiren/www/wp3

EMAIL="shogo@qeight.jp"
BASE="akishimajichiren.sakura.ne.jp/wp3"

create_site() {
    slug="$1"
    title="$2"
    url="${BASE}/${slug}/"

    if wp site list --field=url 2>/dev/null | grep -q "/${slug}/"; then
        echo "SKIP: ${slug} (${title}) — already exists"
        return 0
    fi

    echo "CREATE: ${slug} (${title})"
    wp site create --slug="${slug}" --title="${title}" --email="${EMAIL}"
    wp theme activate akishima --url="${url}"
    wp option update blogname "${title}" --url="${url}"
    wp user add-role qeight administrator --url="${url}" 2>/dev/null || true
    echo "DONE: ${slug} → https://${url}"
}

create_site "block-01" "第1ブロック"
create_site "block-02" "第2ブロック"
create_site "block-03" "第3ブロック"
create_site "block-04" "第4ブロック"
create_site "block-05" "第5ブロック"
create_site "block-06" "第6ブロック"
create_site "block-07" "第7ブロック"
create_site "block-08" "第8ブロック"
create_site "block-09" "第9ブロック"
create_site "block-10" "第10ブロック"
create_site "block-11" "第11ブロック"
create_site "block-12" "第12ブロック"
create_site "block-13" "第13ブロック"
create_site "block-14" "第14ブロック"
create_site "block-15" "第15ブロック"
create_site "block-16" "第16ブロック"
create_site "block-17" "第17ブロック"
create_site "block-18" "第18ブロック"
create_site "block-19" "第19ブロック"
create_site "block-20" "第20ブロック"
create_site "block-21" "第21ブロック"

echo ""
echo "=== ブロック子サイト一覧 ==="
wp site list --fields=blog_id,url | grep 'block-' || true
echo ""
echo "=== done ==="
