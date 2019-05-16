docker build -t fshd_syncthing:0.1 .

#docker rm -f fshd_syncthing
chmod -R 777 /volume1/fshd/www/

docker run -d --restart=always \
  -v /volume1/fshd/www/:/srv/data/ \
  -v /volume1/fshd/syncthing/srv/syncthing:/srv/config \
  -p 8384:8384 -p 22000:22000 -p 21027:21027/udp \
  --name fshd_syncthing \
  fshd_syncthing:0.1

