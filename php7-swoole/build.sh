docker build -t swoole-server:0.1 .
docker run -p 8586:8586 -p 9501:9501 \
	-d --restart=always \
	--name=swoole-server \
	-v ./public:/home/swoole \
	swoole-server:0.1 \
	sh -c 'php /home/swoole/server.php'