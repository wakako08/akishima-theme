#!/bin/sh
# 第3ブロックの子サイトを作成（マップのスラッグ: 03-18, 03-19, 03-20, 03-22）
cd /home/akishimajichiren/www/wp3

wp site create --slug=03-18 --title=site318 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/03-18/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/03-18/

wp site create --slug=03-19 --title=site319 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/03-19/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/03-19/

wp site create --slug=03-20 --title=site320 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/03-20/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/03-20/

wp site create --slug=03-22 --title=site322 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/03-22/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/03-22/

echo "=== done ==="
wp site list --fields=blog_id,url
