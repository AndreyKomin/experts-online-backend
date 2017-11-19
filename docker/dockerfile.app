FROM recatch/php7-nginx-s6-alpine:latest


ENTRYPOINT ["/init"]
WORKDIR /home/www/app

EXPOSE 80

# Add user and group with IDs, matching current host user (developer)
ARG hostUID=1000
ARG hostGID=1000
ENV hostUID=$hostUID
ENV hostGID=$hostGID
RUN echo "uid:gid=$hostUID:$hostGID" && \
    addgroup -g $hostGID host && \
    adduser -S -u $hostUID -G host host

## Unlike production Docker, we can add some productivity tools
RUN apk add --update make mc tmux

COPY root/nginx/ /etc/nginx/
COPY root/php/php-fpm.conf /etc/php7/


