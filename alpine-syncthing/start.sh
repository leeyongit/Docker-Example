#!/bin/bash
# strict mode http://redsymbol.net/articles/unofficial-bash-strict-mode/
set -euo pipefail
IFS=$'\n\t'

if [ ! -f /srv/config/config.xml ]; then
  echo "generating config"
  /srv/syncthing/syncthing --generate="/srv/config"
  sed -e "s/path=\"\/root\/Sync\"/path=\"\/srv\/data\/default\"/" -i /srv/config/config.xml
  sed -e "s/<address>127.0.0.1:8384/<address>0.0.0.0:8384/" -i /srv/config/config.xml
fi

# 设置权限，以便我们可以访问数据卷
chown -R syncthing:users /srv/config /srv/data /srv/syncthing 
chmod -R 770 /srv/config /srv/data /srv/syncthing

# 设置配置文件目录
/srv/syncthing/syncthing -home=/srv/config

