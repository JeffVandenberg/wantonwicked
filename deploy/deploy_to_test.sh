#!/usr/bin/env bash

ssh gamingsandbox@gamingsandbox.com
cd ~/ww-test-git
git pull
php ~/tools/composer.phar self-update
php ~/tools/composer.phar install
