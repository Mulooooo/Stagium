set -euo pipefail
source ./.env
mariadb -u "$DB_USER" -p"$DB_PASS" < database/init.sql
mariadb -u "$DB_USER" -p"$DB_PASS" < database/seed.sql