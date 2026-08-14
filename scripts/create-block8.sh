#!/bin/sh
# 第8ブロック（08-46, 08-47, 08-48, 08-50）
cd /home/akishimajichiren/www/wp3

wp site create --slug=08-46 --title=site846 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/08-46/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/08-46/

wp site create --slug=08-47 --title=site847 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/08-47/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/08-47/

wp site create --slug=08-48 --title=site848 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/08-48/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/08-48/

wp site create --slug=08-50 --title=site850 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/08-50/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/08-50/

echo "=== done ==="
wp site list --fields=blog_id,url
