# Syncthing Discovery Server
# https://github.com/syncthing/discosrv/releases

FROM alpine:3.3 
EXPOSE 8443 

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories
RUN adduser -D user
VOLUME /home/user

RUN mkdir /home/src/
COPY src .
RUN set -x \	
 && apk --no-cache --virtual .temp_del add ca-certificates \
 && mv stdiscosrv /usr/local/bin/stdiscosrv \
 && apk del .temp_del
USER user 
CMD ["stdiscosrv",\
    "-key",          "/cert/key.pem",\
    "-cert",         "/cert/cert.pem",\
    "-limit-avg",    "10",\
    "-limit-cache",  "25000",\
    "-stats-file",   "/home/user/stats"]
	
