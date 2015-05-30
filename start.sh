#!/bin/sh
apache2 -k  start
tail -f /var/log/apache2/*

