#!/bin/bash


# This OUR method to clean the cache :P
rm -rf tmp/*

# Sync the vendor packages with the `composer.lock` specifications
composer install -n

# Some CakePHP optimizations
composer dumpautoload -o
bin/cake plugin assets symlink


exit 0
