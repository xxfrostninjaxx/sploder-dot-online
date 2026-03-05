#!/bin/bash

# Check if environment parameter is provided
ENVIRONMENT=${1:-dev}

echo "Bootstrapping database for environment: $ENVIRONMENT"

DB_USER="${POSTGRES_USERNAME:-sploder}"
DB_PASS="${POSTGRES_PASSWORD:-geoff}"
DB_NAME="${POSTGRES_DB:-sploder}"
DB_PORT="${POSTGRES_PORT:-5432}"
DB_HOST="${POSTGRES_HOST:-db}"

echo "Using database: $DB_NAME (user=$DB_USER, host=$DB_HOST, port=$DB_PORT, sslmode=$DB_SSLMODE)"

echo "Refreshing collation versions for system databases..."
psql -U $DB_USER -d postgres -c "ALTER DATABASE postgres REFRESH COLLATION VERSION;" || true
psql -U $DB_USER -d postgres -c "ALTER DATABASE template1 REFRESH COLLATION VERSION;" || true

if [ "$ENVIRONMENT" = "dev" ]; then
    # Drop & recreate role and db (destructive)
    psql -U $DB_USER -d postgres $PSQL_CONN --command="DROP DATABASE IF EXISTS $DB_NAME;"
    psql -U $DB_USER -d postgres $PSQL_CONN --command="DROP ROLE IF EXISTS $DB_USER;"
    psql -U $DB_USER -d postgres $PSQL_CONN --command="CREATE ROLE $DB_USER WITH LOGIN PASSWORD '$DB_PASS' CREATEDB;"
    psql -U $DB_USER -d postgres $PSQL_CONN --command="CREATE DATABASE $DB_NAME OWNER $DB_USER;"

elif [ "$ENVIRONMENT" = "prod" ]; then
    # Ensure database exists
    psql -U "$DB_USER" -d postgres $PSQL_CONN --tuples-only --no-align -c \
  "SELECT 1 FROM pg_database WHERE datname = '$DB_NAME';" | grep -q 1 \
  || psql -U "$DB_USER" -d postgres $PSQL_CONN -c "CREATE DATABASE $DB_NAME OWNER $DB_USER;"
else
    echo "Invalid environment: $ENVIRONMENT"
    echo "Usage: bootstrap.sh [dev|prod]"
    exit 1
fi

psql -U "$DB_USER" -d "$DB_NAME" $PSQL_CONN -f /bootstrap/sploder.sql

echo "Database schema loaded successfully"

# Create environment-specific data
if [ "$ENVIRONMENT" = "dev" ]; then
    echo "Creating development mock data..."
    
    # TEST USERS (for development only)
    psql -U sploder -d sploder --command="\
      insert into members (username, password, joindate, lastlogin, perms, boostpoints, lastpagechange, ip_address, isolate)\
      values ('test', '\$2y\$10\$rLev0HX15rWPzp8j928yG.2uL9DGWYLcr8fxBnJmYyaeFPogxdiI2', 1733445270, 1733445270, 'MRE', 250, 1000, '127.0.0.1', true);"
    psql -U sploder -d sploder --command="\
      insert into members (username, password, joindate, lastlogin, perms, boostpoints, lastpagechange, ip_address, isolate)\
      values ('test2', '\$2y\$10\$rLev0HX15rWPzp8j928yG.2uL9DGWYLcr8fxBnJmYyaeFPogxdiI2', 1733445270, 1733445270, 'MRE', 250, 1000, '127.0.0.1', true);"
    
    echo "Created development test users: test, test2 (password: 'password' for both)"

    # DUMMY GAMES (for development only)
    for i in $(seq 1 50);
    do
        psql -U sploder -d sploder --command="\
            insert into games (author, title, date, description, g_swf, user_id, isprivate, ispublished)\
            values ('test', 'gtest$i', timezone('utc', now()), 'foobar', 0, 1, 0, 1);"

        psql -U sploder -d sploder --command="\
            insert into game_tags (g_id, tag)\
            values ($i, 'foobar');"
    done
    echo "Created 50 dummy games"

    # DUMMY DELETIONS (for development only)
    for i in $(seq 1 20);
    do
        psql -U sploder -d sploder --command="\
            insert into pending_deletions (g_id, deleter, reason, timestamp)
            values ($i, 'test', 'stuff$i', timezone('utc', now()));"
    done
    echo "Created 20 pending deletions"

    # DUMMY GRAPHIC (for development only)
    psql -U sploder -d sploder --command="\
      insert into graphics (version, userid, isprivate, ispublished)\
      values (0, 1, false, true);"
    echo "Created dummy graphic"
    
    echo "Development mock data creation completed!"
    
elif [ "$ENVIRONMENT" = "prod" ]; then
    echo "Production environment selected. No mock data will be created."
else
    echo "Invalid environment: $ENVIRONMENT"
    echo "Usage: bootstrap.sh [dev|prod]"
    echo "No additional data created"
fi

echo "Bootstrap completed for $ENVIRONMENT environment"