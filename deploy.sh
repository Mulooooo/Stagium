#!/usr/bin/env bash
set -euo pipefail

DEST="/srv/http/stagium"
USER="http"

sudo rsync -a --delete --exclude='.git' ./ "$DEST/"

sudo chown -R "$USER:$USER" "$DEST"

sudo -u $USER composer install --working-dir="$DEST" --no-interaction

sudo systemctl restart httpd