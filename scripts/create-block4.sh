#!/bin/sh
# 第4ブロックの子サイトを作成（マップのスラッグ: 04-23 〜 04-32, 04-104）
cd /home/akishimajichiren/www/wp3

wp site create --slug=04-23 --title=site423 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-23/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-23/

wp site create --slug=04-24 --title=site424 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-24/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-24/

wp site create --slug=04-26 --title=site426 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-26/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-26/

wp site create --slug=04-27 --title=site427 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-27/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-27/

wp site create --slug=04-28 --title=site428 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-28/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-28/

wp site create --slug=04-29 --title=site429 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-29/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-29/

wp site create --slug=04-30 --title=site430 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-30/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-30/

wp site create --slug=04-31 --title=site431 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-31/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-31/

wp site create --slug=04-32 --title=site432 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-32/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-32/

wp site create --slug=04-104 --title=site4104 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/04-104/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/04-104/

echo "=== done ==="
wp site list --fields=blog_id,url
