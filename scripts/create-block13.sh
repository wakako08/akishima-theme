#!/bin/sh
# 第13ブロック（13-78, 13-81）
cd /home/akishimajichiren/www/wp3

wp site create --slug=13-78 --title=site1378 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/13-78/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/13-78/

wp site create --slug=13-81 --title=site1381 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/13-81/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/13-81/

echo "=== done ==="
wp site list --fields=blog_id,url
