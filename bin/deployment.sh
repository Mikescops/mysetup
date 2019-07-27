#!/bin/bash


# Make the Cake program executable
if [[ ! -x bin/cake ]]; then
    chmod +x bin/cake
fi

# Enable maintenance mode !
bin/cake Setup.MaintenanceMode activate

# Clear the application caches
bin/cake cache clear_all

# Sync the vendor packages with the `composer.lock` specifications
COMPOSER_ALLOW_SUPERUSER=true composer install -o -n --no-dev

# Some CakePHP optimizations
bin/cake plugin assets symlink

# Clear the model caches and start the migrations process
bin/cake schema_cache clear
bin/cake migrations migrate --no-lock

# Update the translation binary catalogs from sources !
for file in src/Locale/*_*/*.po; do
    msgfmt -o $(dirname "${file}")"/"$(basename "${file}" .po)".mo" "${file}"
done

# Vendor packages manual patches for newer versions of CakePHP
sed -i 's/->config/->getConfig/1' vendor/tanuck/cakephp-markdown/src/View/Helper/MarkdownHelper.php
sed -i 's/->layout/->setLayout/1'             vendor/dereuromark/cakephp-setup/src/Middleware/MaintenanceMiddleware.php
sed -i 's/->className/->setClassName/1'       vendor/dereuromark/cakephp-setup/src/Middleware/MaintenanceMiddleware.php
sed -i 's/->templatePath/->setTemplatePath/1' vendor/dereuromark/cakephp-setup/src/Middleware/MaintenanceMiddleware.php

# Disable maintenance mode !
bin/cake Setup.MaintenanceMode deactivate


exit 0
