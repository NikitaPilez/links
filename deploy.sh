#!/bin/bash
tmp=/dev/shm/links
rm -rf ${tmp} && \
git clone -l -s . ${tmp} -b master && \
cd ${tmp} && \
echo "Building..." && \
composer i --ignore-platform-reqs --no-dev --optimize-autoloader && \
rm -rf .env.example .gitattributes composer.lock .git* README.md && \
echo "Deploying..." && \
rsync -av --delete --exclude ".env" --exclude "/storage" --exclude "package.json" ./ user@srv.chats.trade:backend/ && \
ssh user@srv.chats.trade "cd backend && php artisan migrate --force && php artisan optimize && php artisan storage:link" && \
echo "Done in ${SECONDS} sec."

