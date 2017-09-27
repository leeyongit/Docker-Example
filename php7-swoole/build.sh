docker build -t swoole-server:0.1 .
docker run -p 8586:8586 -p 9501:9501 -d --name=swoole-server -v ~/Desktop/docker/docker-swoole/public:/home/swoole swoole-server:0.1 sh -c 'php /home/server.php'