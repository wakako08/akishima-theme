#!/bin/sh
# 第12ブロック（12-68 〜 12-75）
cd /home/akishimajichiren/www/wp3

wp site create --slug=12-68 --title=site1268 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/12-68/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/12-68/

wp site create --slug=12-69 --title=site1269 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/12-69/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/12-69/

wp site create --slug=12-70 --title=site1270 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/12-70/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/12-70/

wp site create --slug=12-71 --title=site1271 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/12-71/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/12-71/

wp site create --slug=12-72 --title=site1272 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/12-72/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/12-72/

wp site create --slug=12-73 --title=site1273 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/12-73/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/12-73/

wp site create --slug=12-74 --title=site1274 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/12-74/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/12-74/

wp site create --slug=12-75 --title=site1275 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/12-75/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/12-75/

echo "=== done ==="
wp site list --fields=blog_id,url
