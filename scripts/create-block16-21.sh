#!/bin/sh
# 第16〜21ブロック（最終バッチ）
cd /home/akishimajichiren/www/wp3

wp site create --slug=16-91 --title=site1691 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/16-91/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/16-91/

wp site create --slug=16-92 --title=site1692 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/16-92/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/16-92/

wp site create --slug=17-94 --title=site1794 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/17-94/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/17-94/

wp site create --slug=17-95 --title=site1795 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/17-95/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/17-95/

wp site create --slug=18-96 --title=site1896 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/18-96/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/18-96/

wp site create --slug=18-97 --title=site1897 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/18-97/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/18-97/

wp site create --slug=19-98 --title=site1998 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/19-98/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/19-98/

wp site create --slug=19-100 --title=site19100 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/19-100/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/19-100/

wp site create --slug=19-102 --title=site19102 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/19-102/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/19-102/

wp site create --slug=20-99 --title=site2099 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/20-99/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/20-99/

wp site create --slug=20-101 --title=site20101 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/20-101/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/20-101/

wp site create --slug=21-103 --title=site21103 --email=shogo@qeight.jp
wp theme activate akishima --url=akishimajichiren.sakura.ne.jp/wp3/21-103/
wp user add-role qeight administrator --url=akishimajichiren.sakura.ne.jp/wp3/21-103/

echo "=== done ==="
wp site list --fields=blog_id,url
