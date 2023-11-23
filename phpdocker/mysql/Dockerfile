FROM mysql:8.0


WORKDIR /var/www/html


COPY ./ /var/www/html

ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_DATABASE=test
ENV MYSQL_PASSWORD=root

EXPOSE 3306

RUN /bin/bash -c 'while ! mysqladmin ping -h"$${MYSQL_HOST:-localhost}" --silent; do sleep 1; done && mysql -h"$${MYSQL_HOST:-localhost}" -P"$${MYSQL_PORT:-3306}" -u"$${MYSQL_USER:-root}" -p"$${MYSQL_PASSWORD:-root}" "$${MYSQL_DATABASE:-test}" < /var/www/html/sql.sql &'