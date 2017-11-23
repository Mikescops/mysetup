#!/bin/bash


# This OUR method to clean the cache :P
rm -rf tmp/*

# Sync the vendor packages with the `composer.lock` specifications
composer install -n

# Some CakePHP optimizations
composer dumpautoload -o
bin/cake plugin assets symlink

# Has the model changed ?
bin/cake orm_cache clear
bin/cake migrations migrate --no-lock


exit 0
