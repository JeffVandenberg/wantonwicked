#!/usr/bin/env bash

echo "cd ~/wantonwicked" > ./deploy_script
echo "echo $CIRCLE_BUILD_NUM > build_number" >> ./deploy_script
echo "git pull" >> ./deploy_script
echo "bin/cake migrations migrate" >> ./deploy_script
echo "bin/cake orm_cache clear" >> ./deploy_script
echo "php ~/tools/composer.phar self-update" >> ./deploy_script
echo "php ~/tools/composer.phar install --no-dev" >> ./deploy_script
echo "rm webroot/chat/js/cache/compiled-*" >> ./deploy_script
echo "cat webroot/chat/js/*.js > webroot/chat/js/cache/compiled-${CIRCLE_BUILD_NUM}.js" >> ./deploy_script

cat deploy_script | ssh -t gamingsandbox@gamingsandbox.com
