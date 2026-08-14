#!/bin/sh
cd /home/akishimajichiren/www/wp3

wp site create --slug=01-06 --title=site06 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/01-06/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/01-06/

wp site create --slug=01-07 --title=site07 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/01-07/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/01-07/

wp site create --slug=01-08 --title=site08 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/01-08/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/01-08/

wp site create --slug=01-09 --title=site09 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/01-09/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/01-09/

wp site create --slug=01-11 --title=site11 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/01-11/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/01-11/

echo "=== done ==="
wp site list --fields=blog_id,url
