#!/bin/sh
# 第14ブロック（14-82）
cd /home/akishimajichiren/www/wp3

wp site create --slug=14-82 --title=site1482 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/14-82/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/14-82/

echo "=== done ==="
wp site list --fields=blog_id,url
