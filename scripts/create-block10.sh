#!/bin/sh
# 第10ブロック（10-56 〜 10-62）
cd /home/akishimajichiren/www/wp3

wp site create --slug=10-56 --title=site1056 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/10-56/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/10-56/

wp site create --slug=10-57 --title=site1057 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/10-57/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/10-57/

wp site create --slug=10-58 --title=site1058 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/10-58/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/10-58/

wp site create --slug=10-59 --title=site1059 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/10-59/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/10-59/

wp site create --slug=10-60 --title=site1060 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/10-60/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/10-60/

wp site create --slug=10-61 --title=site1061 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/10-61/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/10-61/

wp site create --slug=10-62 --title=site1062 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/10-62/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/10-62/

echo "=== done ==="
wp site list --fields=blog_id,url
