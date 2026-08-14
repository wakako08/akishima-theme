#!/bin/sh
# 第6ブロックの子サイトを作成（マップのスラッグ: 06-39, 06-40, 06-42）
cd /home/akishimajichiren/www/wp3

wp site create --slug=06-39 --title=site639 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/06-39/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/06-39/

wp site create --slug=06-40 --title=site640 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/06-40/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/06-40/

wp site create --slug=06-42 --title=site642 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/06-42/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/06-42/

echo "=== done ==="
wp site list --fields=blog_id,url
