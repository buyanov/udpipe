FROM php:7.3-cli

RUN docker-php-ext-install pcntl

COPY . /usr/src/udpipe
WORKDIR /usr/src/udpipe

RUN chmod 0644 ./bin/udpipe

EXPOSE 2514

CMD [ "php", "./bin/udpipe", "server:start" ]