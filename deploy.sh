#!/usr/bin/env bash
set -euo pipefail

DEST="/srv/http/stagium"
USER="http"

sudo rsync -a --delete --exclude='.git' ./ "$DEST/"

sudo chown -R "$USER:$USER" "$DEST"

sudo systemctl restart httpd