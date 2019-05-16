#docker build -t syncthing:0.1 .

docker rm -f syncthing

docker run -d --restart=always \
  -v ~/Desktop/docker/docker-syncthing/srv/sync:/srv/data \
  -v ~/Desktop/docker/docker-syncthing/srv/syncthing:/srv/config \
  -p 22000:22000 -p 8080:8080 -p 21027:21027/udp \
  --name syncthing \
  syncthing:0.1



