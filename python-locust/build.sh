docker build -t locust-server:0.1 .
chmod -R 0777 src
docker run -p 8000:8000 -d -v ~/Desktop/workspace/docker/python-locust/src:/home --name=locust-server locust-server:0.1
	# /bin/sh -c 'locust -f /home/locust_file.py' --restart=always

	