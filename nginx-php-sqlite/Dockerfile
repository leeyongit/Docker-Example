#
# Dockerfile for nginx php sqlite
#

FROM alpine:3.6
MAINTAINER leeyongit <leeyongit@163.com>

# 安装包源切到中科大, 国内访问加速
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories && \

# 设置默认时区为亚洲/上海 (没有北京可选)
	apk add --no-cache tzdata && \
	cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime && \
	echo "Asia/Shanghai" > /etc/timezone && \
	apk del tzdata

RUN apk add --no-cache nginx sqlite php5-fpm php5-pdo_sqlite php5-ctype php5-iconv php5-xmlrpc php5-mcrypt \
	php5-sockets php5-curl php5-json php5-phar php5-openssl php5-mysql php5-pdo_mysql php5-gd php5-xml php5-dom && \
	rm -rf /var/cache/apk/*

ADD ./conf /app/
ADD ./start.sh /app/
RUN cp /app/nginx.conf /etc/nginx/ && \
	cp /app/php-fpm.conf /etc/php5/ && \
	chmod +x /app/start.sh

EXPOSE 80
EXPOSE 443
VOLUME ["/data"]

CMD ["/app/start.sh"]