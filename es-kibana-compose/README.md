# ES、Kinbana

## 安装ik分词插件：
```
// 进入容器es
docker exec -it es7 /bin/bash
// 使用bin目录下的elasticsearch-plugin install安装ik插件
/opt/bitnami/elasticsearch/bin/elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v7.9.2/elasticsearch-analysis-ik-7.9.2.zip
// 再重启下容器
docker restart es
```

### 查看ik分词器是否安装成功
```sh
/opt/bitnami/elasticsearch/bin/elasticsearch-plugin list
```

错误：
`Invalid kernel settings. Elasticsearch requires at least: vm.max_map_count = 262144`
解决：
```sh
sysctl -w vm.max_map_count=262144
```