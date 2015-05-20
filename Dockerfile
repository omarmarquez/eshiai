FROM 	httpd:latest
RUN	apt-get -y update && \
	apt-get -y install libapache2-mod-php5  php5-mysql php5-sqlite
RUN	a2enmod rewrite
WORKDIR /var/www/html
COPY 	. /var/www/html
COPY	judoapp.conf /etc/apache2/conf-enabled/
RUN	chown -R www-data:www-data app
CMD	["/var/www/html/start.sh"]
