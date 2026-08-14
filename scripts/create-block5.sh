#!/bin/sh
# 第5ブロックの子サイトを作成（マップのスラッグ: 05-33 〜 05-36）
cd /home/akishimajichiren/www/wp3

wp site create --slug=05-33 --title=site533 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/05-33/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/05-33/

wp site create --slug=05-34 --title=site534 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/05-34/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/05-34/

wp site create --slug=05-35 --title=site535 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/05-35/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/05-35/

wp site create --slug=05-36 --title=site536 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/05-36/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/05-36/

echo "=== done ==="
wp site list --fields=blog_id,url
