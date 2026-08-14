#!/bin/sh
# 第9ブロック（09-51, 09-52, 09-53, 09-54, 09-79）
cd /home/akishimajichiren/www/wp3

wp site create --slug=09-51 --title=site951 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/09-51/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/09-51/

wp site create --slug=09-52 --title=site952 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/09-52/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/09-52/

wp site create --slug=09-53 --title=site953 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/09-53/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/09-53/

wp site create --slug=09-54 --title=site954 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/09-54/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/09-54/

wp site create --slug=09-79 --title=site979 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/09-79/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/09-79/

echo "=== done ==="
wp site list --fields=blog_id,url
