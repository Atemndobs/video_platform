#!/bin/bash

if [ "$2" == "-db" ]; then
    echo "rebuilding database ..."

bin/console doctrine:schema:drop -n -q --force --full-database
rm src/Migrations/*.php
bin/console make:migration
bin/console doctrine:migrations:migrate -n -q
bin/console doctrine:fixtures:load -n -q

fi

if [ -n "$1" ]; then
php bin/phpunit $1

else

php bin/phpunit
fi

# /bin/bash tests.sh tests -db    (for cleanig up db and resetting with values from fixtures)
# /bin/bash tests.sh tests        (for running the tests)
