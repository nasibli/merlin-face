FROM ubuntu:focal

RUN apt-get update && apt-get install -y \
    beanstalkd; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /var/www/merlin-face
