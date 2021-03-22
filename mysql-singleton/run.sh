docker pull mysql:5.7
docker run -p 3306:3306 \
    -v /data/docker/docker-data/mysql/mysql_data:/var/lib/mysql \
    -v /data/my.cnf:/etc/mysql/my.cnf \
    --name ibus-mysql \
    -e MYSQL_ROOT_PASSWORD=ibus@2021#! -d mysql:5.7