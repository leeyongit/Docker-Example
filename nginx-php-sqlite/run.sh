#!/bin/sh
docker build --no-cache -t fshd_web:latest .
#docker run -d --restart=always -p 8000:80 -v /volume1/fshd/www/:/data/www --name=webapp fshd_web:latest
docker run -d --restart=always -p 8000:80 -v ~/Desktop/project/nas/www:/data/www --name=webapp fshd_web:latest