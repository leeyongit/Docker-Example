FROM alpine:3.5

MAINTAINER liyong <liyong@fshd.com>

RUN adduser -D user

ENV SYNCTHING_VERSION 0.14.37
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories

RUN apk --update add bash

# 获取 syncthing
COPY syncthing/syncthing .
RUN mkdir -p /srv/syncthing
RUN mv syncthing /srv/syncthing/syncthing

WORKDIR /srv
RUN adduser -D -u 2000 syncthing
RUN mkdir -p /srv/config
RUN mkdir -p /srv/data

VOLUME ["/srv/data", "/srv/config"]
EXPOSE 8384 22000 21027/udp

ADD ./start.sh /srv/start.sh
RUN chmod 770 /srv/start.sh

ENTRYPOINT ["/srv/start.sh"]

