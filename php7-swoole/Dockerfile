FROM php:7.1-cli
MAINTAINER leeyongit
RUN apt-get update 
RUN pecl install redis \
	&& pecl install swoole \
	&& docker-php-ext-enable redis \
&& docker-php-ext-enable swoole
EXPOSE 8586
WORKDIR /home
COPY src .
CMD php server.php