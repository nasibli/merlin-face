FROM phpdockerio/php74-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    wget \
    zlib1g-dev \
    libmcrypt-dev \
    libzip-dev \
    libsqlite3-dev \
    php7.4-sqlite3 \
    git

WORKDIR /var/www/merlin-face
