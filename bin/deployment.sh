#!/bin/bash


# This OUR method to clean the cache :P
rm -rf tmp/*

# Sync the vendor packages with the `composer.lock` specifications
COMPOSER_ALLOW_SUPERUSER=true composer install -o -n --profile

# Some CakePHP optimizations
bin/cake plugin assets symlink

# Has the model changed ?
bin/cake schema_cache clear
bin/cake migrations migrate --no-lock

# Update the translation binary catalogs from sources !
for file in src/Locale/*_*/*.po; do
    msgfmt -o $(dirname "${file}")"/"$(basename "${file}" .po)".mo" "${file}"
done

# Vendor packages manual patches for newer versions of CakePHP
sed -i 's/->config/->getConfig/1' vendor/tanuck/cakephp-markdown/src/View/Helper/MarkdownHelper.php


exit 0
