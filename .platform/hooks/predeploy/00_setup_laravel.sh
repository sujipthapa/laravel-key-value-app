#!/bin/bash
set -e

sudo chown -R webapp:webapp /var/app/staging/storage /var/app/staging/bootstrap/cache
sudo chmod -R 775 /var/app/staging/storage /var/app/staging/bootstrap/cache
