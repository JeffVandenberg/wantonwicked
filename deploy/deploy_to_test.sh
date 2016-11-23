#!/usr/bin/env bash

ssh gamingsandbox@gamingsandbox.com << EOF
    cd ~/wwtest
    git pull
    php ~/tools/composer.phar self-update
    php ~/tools/composer.phar install
EOF
