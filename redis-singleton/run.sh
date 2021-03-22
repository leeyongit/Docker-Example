 docker run --name ibus-redis -d -p 6379:6379 -v \
    /data/docker/docker-data/redis/redis_data:/data redis --requirepass "ibus_@2021_6379"