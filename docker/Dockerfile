FROM debian:wheezy

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
apt-get install -qqy wget && \
wget http://www.dotdeb.org/dotdeb.gpg && \
apt-key add dotdeb.gpg && \
echo "deb http://ftp.de.debian.org/debian wheezy main" >> /etc/apt/sources.list && \
echo "deb http://packages.dotdeb.org wheezy all" >> /etc/apt/sources.list && \
echo "deb http://packages.dotdeb.org wheezy-php55 all" >> /etc/apt/sources.list &&\
apt-get update -qq && \
apt-get install -y \
git \
apache2 \
php5 \
php5-cgi \
libapache2-mod-php5 \
php5-cli \
php5-curl \
php5-gd \
php5-imagick \
php5-mcrypt \
php5-memcache \
php5-intl \
php5-mysql \
php5-odbc \
php5-xcache \
php5-xmlrpc \
php5-xsl \
php5-xdebug \
php5-dev \
make \
php-mime-type \
php-pear \
curl \
mysql-client \
memcached

RUN pecl install memcache && \
ln -s /var/lib/php5 /var/lib/php && \
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

ADD kali-vhost.conf /etc/apache2/sites-available/kali-vhost.conf

RUN a2dissite default && a2ensite kali-vhost.conf && \
a2enmod headers rewrite ssl vhost_alias php5 && \
a2enmod php5  && \
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN sed -i 's/memory_limit = .*/memory_limit = '512M'/' /etc/php5/apache2/php.ini && \
sed -i 's/^;date.timezone =/date.timezone = Europe\/Paris/' /etc/php5/apache2/php.ini && \
sed -i 's/memory_limit = .*/memory_limit = '-1'/' /etc/php5/cli/php.ini && \
sed -i 's/^;date.timezone =/date.timezone = Europe\/Paris/' /etc/php5/cli/php.ini

EXPOSE 80

WORKDIR /var/www

CMD chmod 777 /var/www/app/cache && \
chmod 777 /var/www/app/logs && \
/usr/sbin/apache2ctl -D FOREGROUND
