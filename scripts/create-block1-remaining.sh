#!/bin/sh
# 第1ブロック残りの子サイトを作成（01-01・01-02はスキップ）
# 本番サーバーで実行: cd /home/akishimajichiren/www/wp3 && sh create-block1-remaining.sh

set -e

cd /home/akishimajichiren/www/wp3

EMAIL="shogo@qeight.jp"
BASE="akishimajichiren.sakura.ne.jp/wp3"

create_site() {
    slug="$1"
    title="$2"
    url="${BASE}/${slug}/"

    if wp site list --field=url | grep -q "/${slug}/"; then
        echo "SKIP: ${slug} (${title}) — already exists"
        return 0
    fi

    echo "CREATE: ${slug} (${title})"
    wp site create --slug="${slug}" --title="${title}" --email="${EMAIL}"
    wp theme activate akishima --url="${url}"
    wp user add-role qeight administrator --url="${url}"
    echo "DONE: ${slug}"
}

create_site "01-03" "郷地第三自治会"
create_site "01-04" "五月自治会"
create_site "01-05" "東町第五自治会"
create_site "01-06" "東町東町会"
create_site "01-07" "東町親睦会"
create_site "01-08" "東町中央自治会"
create_site "01-09" "昭島団地自治会"
create_site "01-11" "郷地玉川自治会"

echo ""
echo "=== 01-02 サイト名を修正 ==="
wp option update blogname "郷地第二自治会" --url="${BASE}/01-02/"

echo ""
echo "=== 作成済みサイト一覧 ==="
wp site list --fields=blog_id,url
