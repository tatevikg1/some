#!/bin/bash

setphp 8.1

FILE=.env
composer install
if test -f "$FILE";
    then
        echo "$FILE file exists."
    else
        cp .env.example .env
fi
#php artisan key:generate
#php artisan migrate
#php artisan db:seed

# setup file permissions
sudo usermod -a -G www-data t
sudo chown -R $USER:www-data .
sudo find . -type f -exec chmod 664 {} \;
sudo find . -type d -exec chmod 755 {} \;
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache

# make this file executable
sudo chmod +x start.sh

#nvm use 14
#npm run dev

# php artisan passport:client --personal
php artisan storage:link

exit

# execute from rabbitmq container or wherever is rabbitmq
# rabbitmqctl add_vhost some
# rabbitmqctl set_user_tags guest administrator management
# rabbitmqctl set_permissions -p some guest ".*" ".*" ".*"
