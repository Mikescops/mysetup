#!/bin/bash

composer dumpautoload -o
bin/cake plugin assets symlink

rm -rf tmp/*

exit 0
