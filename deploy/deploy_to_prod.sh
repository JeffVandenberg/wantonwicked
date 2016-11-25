#!/usr/bin/env bash

ssh gamingsandbox@gamingsandbox.com << EOF
    cd ~/wantonwicked
    git pull
    php ~/tools/composer.phar self-update
    php ~/tools/composer.phar install
EOF

echo $CIRCLE_BUILD_NUM > build_number
scp build_number gamingsandbox@gamingsandbox.com:~/wantonwickde/build_number
rm build_number
