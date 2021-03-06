#!/usr/bin/env bash

# Locale issues
sudo apt-get update
sudo apt-get install language-pack-en language-pack-en-base -y
sudo locale-gen en_US.UTF-8
export LC_ALL="en_US.UTF-8"

# /data/
sudo mkdir -p /data/db/
sudo mkdir -p /data/storage/
sudo chown `id -u` /data/db

# ###############
# APT-GET SECTION
# ###############
sudo add-apt-repository ppa:mc3man/trusty-media -y
sudo apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 8E51A6D660CD88D67D65221D90BD7EACED8E640A
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
sudo LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php -y
echo 'deb http://downloads-distro.mongodb.org/repo/ubuntu-upstart dist 10gen' | sudo tee /etc/apt/sources.list.d/mongodb.list
sudo apt-get update

sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password 1234'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password 1234'

sudo apt-get install -y ffmpeg curl php7.0 php7.0-fpm php7.0-mysql php7.0-zip php7.0-curl php7.0-xml php7.0-gd php7.0-bcmath php7.0-mbstring php7.0-dom git npm nginx nginx-extras sphinxsearch rabbitmq-server sendmail mysql-client mysql-server php-pear php7.0-dev pkg-config libssl-dev libsslcommon2-dev php7.0-intl

# ######
# XDEBUG
# ######
sudo pecl install xdebug

# #####
# MONGO
# #####
sudo apt-get install -y mongodb-org
sudo service mongod restart

# ##############
# PHP7.0-MONGODB
# ##############
sudo pecl install mongodb

# ##########
# UPDATE NPM
# ##########
sudo npm install -g n
sudo n latest

# #####
# MYSQL
# #####
mysql -uroot -p"1234" -e "CREATE DATABASE cass"
mysql -uroot -p"1234" -e "CREATE DATABASE cass_testing"

# #####
# CLONE
# #####
cd /opt/cass
git pull && git submodule init && git submodule update && git submodule status
sudo cp -R /support-files/vagrant/env/all/root/* /
sudo cp -R /support-files/vagrant/env/dev/root/* /

# #######
# Backend
# #######
cd /opt/cass/src/backend/

# Install composer.phar
cd /tmp
sudo curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/bin/
sudo chmod a+x /usr/bin/composer.phar
sudo ln -s /usr/bin/composer.phar /usr/bin/composer

# Install composer dependencies
cd /opt/cass/src/backend/
sudo composer.phar install

# error log
sudo touch /var/log/php-errors.log
sudo chown -R www-data:www-data /var/log/php-errors.log

# db
cd /opt/cass/src/backend
./vendor/bin/phinx migrate -e cass
./vendor/bin/phinx migrate -e cass_testing

# ########
# Frontend
# ########
cd /opt/cass/src/frontend

sudo ln -s /usr/bin/nodejs /usr/bin/node

sudo npm install -g typings webpack
typings install

# #######
# SWAGGER
# #######
sudo mkdir /opt/swagger
cd /opt/swagger
sudo rm -rf swagger-ui/
sudo git clone https://github.com/swagger-api/swagger-ui.git
cd swagger-ui
sudo git checkout v2.2.3
sudo npm install
sudo npm run build
sudo mv dist api-docs
sudo chown -R www-data:www-data /opt/swagger

#########
# PHPUNIT
#########
cd /tmp
wget https://phar.phpunit.de/phpunit.phar
chmod +x phpunit.phar
sudo mv phpunit.phar /usr/local/bin/phpunit

# #####
# NGINX
# #####
sudo ln -s /etc/nginx/sites-available/cass /etc/nginx/sites-enabled/cass
sudo service nginx restart

# #######
# MIGRATE
# #######
vm-migrate.sh