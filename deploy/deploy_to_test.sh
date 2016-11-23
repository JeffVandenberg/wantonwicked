#!/usr/bin/env bash

ssh gamingsandbox@gamingsandbox.com << EOF
    cd ~/wwtest
    git pull
    php ~/tools/composer.phar self-update
    php ~/tools/composer.phar install
EOF

echo $CIRCLE_BUILD_NUM > build_number
scp buildnumber gamingsandbox@gamingsandbox.com:~/wwtest/build_number
rm build_number
