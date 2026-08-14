#!/bin/sh
# 第11ブロック（11-63 〜 11-67）
cd /home/akishimajichiren/www/wp3

wp site create --slug=11-63 --title=site1163 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/11-63/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/11-63/

wp site create --slug=11-64 --title=site1164 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/11-64/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/11-64/

wp site create --slug=11-65 --title=site1165 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/11-65/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/11-65/

wp site create --slug=11-66 --title=site1166 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/11-66/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/11-66/

wp site create --slug=11-67 --title=site1167 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/11-67/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/11-67/

echo "=== done ==="
wp site list --fields=blog_id,url
