#!/bin/bash


# Let's upgrade the vendor packages with the latest versions (should be tested during development)
composer install

# We can't get Composer to do what we want with this tweaked dependence, so this is the current workaround...
git checkout vendor/loadsys/cakephp-sitemap/

# Some CakePHP optimizations
composer dumpautoload -o
bin/cake plugin assets symlink

# This OUR method to clean the cache :P
rm -rf tmp/*


exit 0
