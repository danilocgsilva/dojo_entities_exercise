#!/bin/bash

DOCKER_ENVIRONMENT=docker_environment

mkdir $DOCKER_ENVIRONMENT
cd $DOCKER_ENVIRONMENT
git clone git@github.com:danilocgsilva/simplified_lamp_docker.git .
./setup_env.sh

