#!/bin/sh
# 第7ブロック（07-43, 07-44, 07-45）
cd /home/akishimajichiren/www/wp3

wp site create --slug=07-43 --title=site743 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/07-43/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/07-43/

wp site create --slug=07-44 --title=site744 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/07-44/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/07-44/

wp site create --slug=07-45 --title=site745 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/07-45/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/07-45/

echo "=== done ==="
wp site list --fields=blog_id,url
