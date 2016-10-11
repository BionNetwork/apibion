#!/usr/bin/env bash

if [ -z $1 ]; then
    echo "Error: env is not set";
    exit;
fi
ENV=$1
echo "ENVIRONMENT: $ENV";

PROMPT_MESSAGE="  -----------------------------\n-- This will drop database --\n-- Print 'yes' to continue --\n-----------------------------"
echo -e $PROMPT_MESSAGE
read -r -p ">" RESPONSE
if ! [[ ${RESPONSE} =~ ^yes$ ]]; then echo "Operation cancelled" && exit; fi


CONSOLE=app/console
$CONSOLE doctrine:database:drop --env $ENV --force --if-exists
$CONSOLE doctrine:database:create --env $ENV
$CONSOLE doctrine:migration:migrate --env $ENV -n

#$CONSOLE bi:cards:update --force --env $ENV
#$CONSOLE bi:arguments:update --force --env $ENV
#$CONSOLE bi:categories:load --force --env $ENV
