#!/bin/sh
# 第15ブロック（15-83 〜 15-90）
cd /home/akishimajichiren/www/wp3

wp site create --slug=15-83 --title=site1583 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/15-83/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/15-83/

wp site create --slug=15-84 --title=site1584 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/15-84/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/15-84/

wp site create --slug=15-85 --title=site1585 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/15-85/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/15-85/

wp site create --slug=15-86 --title=site1586 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/15-86/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/15-86/

wp site create --slug=15-87 --title=site1587 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/15-87/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/15-87/

wp site create --slug=15-88 --title=site1588 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/15-88/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/15-88/

wp site create --slug=15-89 --title=site1589 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/15-89/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/15-89/

wp site create --slug=15-90 --title=site1590 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/15-90/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/15-90/

echo "=== done ==="
wp site list --fields=blog_id,url
