docker build -t locust-server:0.1 .
chmod -R 0777 src
docker run -p 8089:8089 -d -v /Users/leeyong/Desktop/docker-python/src:/home --name=locust-server  locust-server:0.1 /bin/sh -c 'locust -f /home/locust_file.py --host=http://dev.fshd.com'