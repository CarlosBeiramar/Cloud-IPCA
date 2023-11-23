FROM mysql:8.0


WORKDIR /var/www/html


COPY ./ /var/www/html

ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_DATABASE=cloud
ENV MYSQL_PASSWORD=root

EXPOSE 3306

RUN /bin/bash -c 'mysql -h 127.0.0.1 -P 3306 -u root -proot cloud < sql.sql'