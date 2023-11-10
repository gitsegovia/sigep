FROM nimmis/apache:14.04

RUN apt-get update && apt-get install -y make gcc git

WORKDIR /

# disable interactive functions
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
apt-get install -y php5 libapache2-mod-php5  \
php5-fpm php5-cli php5-mysqlnd php5-pgsql php5-sqlite php5-redis \
php5-apcu php5-intl php5-imagick php5-mcrypt php5-json php5-gd php5-curl php-pear

WORKDIR /var/www/html

RUN apt-get update -y
RUN apt-get install -y --force-yes apt-utils build-essential
RUN apt-get install -y --force-yes nano

RUN rm /var/www/html/index.html
COPY --chown=www-data:www-data . ./
RUN chmod -R 755 /var/www
RUN cp /sbin/ifconfig /var/www/html/sigep/app/webroot/session_confi
RUN cp /var/www/html/apacheconf/apache2.conf /etc/apache/apache2.conf
RUN cp /var/www/html/apacheconf/05-opcache.ini /etc/php5/cli/conf.d/05-opcache.ini
RUN cp /var/www/html/apacheconf/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/sigep/app/webroot
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf