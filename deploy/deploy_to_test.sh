#!/usr/bin/env bash

echo $CIRCLE_BUILD_NUM > build_number
echo "export BUILD_NUMBER=`cat ~/wwtest/build_number`" > build_number_env
scp build_number* gamingsandbox@gamingsandbox.com:~/wwtest/
rm build_number*

ssh gamingsandbox@gamingsandbox.com << EOF
    cd ~/wwtest
    git pull
    php ~/tools/composer.phar self-update
    php ~/tools/composer.phar install
    source build_number_env
    rm build_number_env
    cat app/webroot/chat/js/*.js > app/webroot/chat/js/cache/compiled-$BUILD_NUMBER.js
EOF
