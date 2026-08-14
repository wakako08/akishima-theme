#!/bin/sh
# 第2ブロックの子サイトを作成（マップのスラッグ: 02-12 〜 02-16）
cd /home/akishimajichiren/www/wp3

wp site create --slug=02-12 --title=site212 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/02-12/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/02-12/

wp site create --slug=02-13 --title=site213 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/02-13/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/02-13/

wp site create --slug=02-14 --title=site214 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/02-14/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/02-14/

wp site create --slug=02-15 --title=site215 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/02-15/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/02-15/

wp site create --slug=02-16 --title=site216 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/02-16/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/02-16/

echo "=== done ==="
wp site list --fields=blog_id,url
