FROM ubuntu:15.10
MAINTAINER liyong <liyong@fshd.com>

ENV SYNCTHING_VERSION 0.14.37

RUN cp /etc/apt/sources.list /etc/apt/sources.list.bak
COPY sources.list /etc/apt/sources.list

RUN apt-get update \
  && apt-get upgrade -y --no-install-recommends \
  && apt-get autoremove -y \
  && apt-get clean


# 获取 syncthing
COPY syncthing/syncthing .
RUN mkdir -p /srv/syncthing
RUN mv syncthing /srv/syncthing/syncthing

WORKDIR /srv
RUN useradd --no-create-home -g users syncthing
RUN mkdir -p /srv/config
RUN mkdir -p /srv/data

VOLUME ["/srv/data", "/srv/config"]
EXPOSE 8384 22000 21027/udp

ADD ./start.sh /srv/start.sh
RUN chmod 770 /srv/start.sh

ENV UID=1027

ENTRYPOINT ["/srv/start.sh"]

