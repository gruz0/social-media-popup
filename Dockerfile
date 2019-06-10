FROM wordpress:latest
MAINTAINER Alexander Kadyrov <gruz0.mail@gmail.com>

COPY bin/activate_debug.sh /usr/local/bin
RUN apt-get update && \
    apt-get install -y --no-install-recommends apt-utils vim-tiny wget && \
    \
    wget https://getcomposer.org/download/1.8.4/composer.phar && \
    chmod +x ./composer.phar && \
    mv composer.phar /usr/local/bin/composer && \
    \
    # Purge all packages and unused files
    apt-get purge -y apt-utils wget && \
    apt-get autoremove -y && apt-get clean autoclean -y && \
    rm -rf /tmp/* /var/tmp/* /var/lib/apt/archive/* /var/lib/apt/lists/*
