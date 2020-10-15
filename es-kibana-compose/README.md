# ES、Kinbana

## 安装ik分词插件：
```
// 进入容器es
docker exec -it es /bin/bash
// 使用bin目录下的elasticsearch-plugin install安装ik插件
/opt/bitnami/elasticsearch/bin/elasticsearch-plugin install https://github.com/medcl/elasticsearch-analis-ik/releases/download/v7.9.2/elasticsearch-analysis-ik-7.9.2.zip
// 再重启下容器
docker restart es
```