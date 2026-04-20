#!/usr/bin/env bash
set -euo pipefail

DEST="/srv/http/stagium"
USER="http"

sudo rsync -a --delete --exclude='.git' --exclude='storage/' ./ "$DEST/"

sudo mkdir -p "$DEST/storage/cv" "$DEST/storage/lm"

sudo chown -R "$USER:$USER" "$DEST"

sudo -u $USER composer install --working-dir="$DEST" --no-interaction

sudo systemctl restart httpd