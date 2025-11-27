#!/bin/bash
set -e

APP_DIR="/var/app/staging"

echo "==== Migration started at $(date) ===="
cd "$APP_DIR"

sudo -u webapp php artisan migrate --force

echo "==== Migration finished at $(date) ===="
