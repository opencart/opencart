FROM httpd:2.4-alpine

COPY docker/apache/conf/httpd.conf /usr/local/apache2/conf/httpd.conf

RUN mkdir -p /usr/local/apache2/conf/sites-available

COPY docker/apache/conf/opencart.conf /usr/local/apache2/conf/sites-available/opencart.conf

EXPOSE 80
