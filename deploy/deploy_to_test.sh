#!/usr/bin/env bash

ssh gamingsandbox@gamingsandbox.com << EOF
    cd ~/wwtest
    git pull
    php ~/tools/composer.phar self-update
    php ~/tools/composer.phar install
    cat app/webroot/chat/js/*.js > app/webroot/chat/js/cache/compiled-{$CIRLE_BUILD_NUM}.js
EOF

echo $CIRCLE_BUILD_NUM > build_number
scp build_number gamingsandbox@gamingsandbox.com:~/wwtest/build_number
rm build_number
