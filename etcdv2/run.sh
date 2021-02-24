docker run --name etcd2 -d \
    -p 2379:2379 -p 2380:2380 -p 4001:4001 -p 7001:7001 \
    -v ~/temp/data0/etcd:/data \
    wolfdeng/etcd2-docker
