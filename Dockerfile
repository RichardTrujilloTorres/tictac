FROM eboraas/apache:stretch

MAINTAINER Richard Trujillo Torres <richard@desemax.com>

RUN apt update && apt -y upgrade
RUN apt -y install ca-certificates apt-transport-https 

# install utilities
RUN apt -y install \
    wget \
    vim \
    git

# php repository setup
RUN wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add -
RUN echo "deb https://packages.sury.org/php/ stretch main" | tee /etc/apt/sources.list.d/php.list

# install php 7.2 and its modules
RUN apt update && apt -y install \
    php7.2
RUN apt -y install \
    php7.2-cli \
    php7.2-common \
    php7.2-curl \
    php7.2-mbstring \
    php7.2-mysql \
    php7.2-xml \
    php7.2-zip \
    php7.2-gd \
    php7.2-imagick



# apache configuration 
RUN /usr/sbin/a2enmod rewrite 
ADD 000-laravel.conf /etc/apache2/sites-available/
ADD 001-laravel-ssl.conf /etc/apache2/sites-available/
RUN /usr/sbin/a2dissite '*' && /usr/sbin/a2ensite 000-laravel 001-laravel-ssl


# composer installation
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

WORKDIR /var/www/
RUN mv /composer.phar .


# clone the project repository & install its dependencies
RUN git clone https://github.com/RichardTrujilloTorres/tictac
# ADD .env.example .env
WORKDIR /var/www/tictac
RUN php ../composer.phar install

RUN /bin/chown -R www-data:www-data \
    /var/www/tictac/storage \
    /var/www/tictac/storage/logs
RUN /bin/chown -R www-data:www-data /var/www/tictac


# post installation scripts
RUN cp .env.example .env
RUN php artisan migrate
RUN php artisan key:generate

EXPOSE 80
EXPOSE 443

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
