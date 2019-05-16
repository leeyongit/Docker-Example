chomd -R 777 src
docker build -t syncthing-discovery-server:0.1 .
syncthing -generate="~/disco_cert/"
chmod -R 777 ~/disco_cert/
docker run -p 8443:8443 -v ~/disco_cert:/cert -d --restart=always --name=syncthing-discovery syncthing-discovery-server:0.1
# https://(ServerAddress):8443/v2/?id=(Server device ID above) SKF2L77-5IH2RK7-HHFTXKF-CTNW7UV-RVLFNVV-AQAOBH3-WEYV423-ISV3CAH