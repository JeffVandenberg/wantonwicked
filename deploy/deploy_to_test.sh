#!/usr/bin/env bash

echo "cd ~/wwtest" > ./deploy_script
echo "echo $CIRCLE_BUILD_NUM > build_number" >> ./deploy_script
echo "git pull" >> ./deploy_script
echo "php ~/tools/composer.phar self-update" >> ./deploy_script
echo "php ~/tools/composer.phar install" >> ./deploy_script
echo "rm app/webroot/chat/js/compiled-*" >> ./deploy_script
echo "cat app/webroot/chat/js/*.js > app/webroot/chat/js/cache/compiled-${CIRCLE_BUILD_NUM}.js" >> ./deploy_script

cat deploy_script | ssh -t gamingsandbox@gamingsandbox.com
