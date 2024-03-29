FROM phusion/baseimage

RUN curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | bash

RUN \
    add-apt-repository ppa:ondrej/php
RUN	\
	apt-get update


RUN \
  	apt-get install -y \
	    php7.2 \
	    php7.2-bcmath \
	    php7.2-cli \
	    php7.2-common \
	    php7.2-fpm \
	    php7.2-gd \
	    php7.2-gmp \
	    php7.2-intl \
	    php7.2-json \
	    php7.2-mbstring \
	    php7.2-mysqlnd \
	    php7.2-opcache \
	    php7.2-pdo \
	    php7.2-xml \
	    php7.2-phalcon \
	    zip \
	    unzip

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN service php7.2-fpm start

RUN apt-get install -y git

RUN apt-get install -y nginx-full supervisor

RUN apt-get clean
RUN apt-get autoclean

COPY container/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY container/nginx.conf /etc/nginx/sites-enabled/default
COPY container/php.ini /etc/php/7.2/fpm/php.ini

WORKDIR /var/www/

EXPOSE 80 443
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
