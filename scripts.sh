#!/usr/bin/env bash

set -eu

function docker_compose {
    # shellcheck disable=SC2068
    sudo -E docker-compose $@
}

function pull_images {
    docker_compose pull
}

function start_services {
    docker_compose up --build
}

function stop_services {
    docker_compose stop
}

function down_services {
    docker_compose down -v
}

function run_php {
    # shellcheck disable=SC2068
    docker_compose exec php $@
}

function run_composer {
    # shellcheck disable=SC2068
    run_php composer $@
}

function run_symfony {
    # shellcheck disable=SC2068
    run_php bin/console $@
}

function run_robo {
    run_php vendor/bin/robo $@
}

function connect_mysql_shell {
    docker_compose exec db mysql -u${MYSQL_USER} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE}
}

command_full=$@
command_name="$1"
command_args=${command_full#"$command_name"}

echo "command full:   scripts.sh ${command_full}"
echo "command name:   ${command_name}"
echo "command args:  ${command_args}"
echo "command output: "
echo ""

(

case ${command_name} in
    docker-compose)
        docker_compose ${command_args}
        ;;
    start)
        pull_images
        start_services
        ;;
    stop)
        stop_services
        ;;
    down)
        down_services
        ;;
    php)
        run_php ${command_args}
        ;;
    composer)
        run_composer ${command_args}
        ;;
    symfony)
        run_symfony ${command_args}
        ;;
    robo)
        run_robo ${command_args}
        ;;
    mysql)
        connect_mysql_shell
        ;;
    *)
        echo "${command_name} is unknown"
        # shellcheck disable=SC2242
        exit -1
        ;;
esac
)