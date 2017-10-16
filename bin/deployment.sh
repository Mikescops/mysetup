#!/bin/bash


# This OUR method to clean the cache :P
rm -rf tmp/*

# Let's upgrade the vendor packages with the latest versions (should be tested during development)
composer install -n

# Some CakePHP optimizations
composer dumpautoload -o
bin/cake plugin assets symlink


exit 0
