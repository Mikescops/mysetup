#!/bin/bash


# This OUR method to clean the cache :P
rm -rf tmp/*

# Let's upgrade the vendor packages with the latest versions (should be tested during development)
composer install -n

# We can't get Composer to do what we want with this tweaked dependence, so this is the current workaround...
git checkout vendor/loadsys/cakephp_sitemap/

# Some CakePHP optimizations
composer dumpautoload -o
bin/cake plugin assets symlink


exit 0
