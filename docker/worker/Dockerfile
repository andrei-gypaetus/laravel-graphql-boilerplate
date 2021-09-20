FROM sirajul/php74-worker:latest

COPY /docker/worker/worker.conf /etc/supervisor/conf.d/worker.conf

WORKDIR /var/www

RUN mkdir -p /var/www/storage/logs/
RUN touch /var/www/storage/logs/supervisor.log

COPY /docker/worker/entrypoint.sh /entry-point.sh
RUN chmod +x /entry-point.sh

ENTRYPOINT ["/entry-point.sh"]
