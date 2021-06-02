FROM php:7.0-apache
 
RUN apt-get update
RUN apt-get install -y wget lsb-release
RUN curl -O http://packages.couchbase.com/releases/couchbase-release/couchbase-release-1.0-6-amd64.deb && \
    dpkg -i couchbase-release-1.0-6-amd64.deb && \
    rm couchbase-release-1.0-6-amd64.deb
RUN apt-get update && apt-get install -y libcouchbase-dev build-essential zlib1g-dev
RUN pecl install couchbase-2.6.2
RUN docker-php-ext-enable couchbase
