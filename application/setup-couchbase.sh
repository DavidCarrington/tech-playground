#!/bin/bash
set -eux

wait-for-url() {
    echo "Testing $1"
    timeout -s TERM 45 bash -c \
    'while [[ "$(curl -s -o /dev/null -L -w ''%{http_code}'' ${0})" != "200" ]];\
    do echo "Waiting for ${0}" && sleep 1;\
    done' ${1}
    echo "OK!"
    curl -I $1
}
wait-for-url "http://couchbase.1:8091"

# Set up server 1
curl -v http://couchbase.1:8091/pools/default -d memoryQuota=256 -d indexMemoryQuota=256
curl -v http://couchbase.1:8091/node/controller/setupServices -d services=kv
curl -v http://couchbase.1:8091/settings/web -d port=8091 -d username=$COUCHBASE_ADMIN_USERNAME -d password=$COUCHBASE_ADMIN_PASSWORD
curl -v -u $COUCHBASE_ADMIN_USERNAME:$COUCHBASE_ADMIN_PASSWORD http://couchbase.1:8091/pools/default/buckets -d name=my_bucket -d bucketType=couchbase -d ramQuotaMB=128 -d authType=sasl -d saslPassword=my_bucket_PASSWORD
curl -v -u $COUCHBASE_ADMIN_USERNAME:$COUCHBASE_ADMIN_PASSWORD http://couchbase.1:8091/node/controller/rename -d hostname=couchbase.1

# Add servers 2 and 3
curl -u $COUCHBASE_ADMIN_USERNAME:$COUCHBASE_ADMIN_PASSWORD -v http://couchbase.1:8091/controller/addNode -d 'hostname=couchbase.2&user=$COUCHBASE_ADMIN_USERNAME&password=$COUCHBASE_ADMIN_PASSWORD&services=kv'
curl -u $COUCHBASE_ADMIN_USERNAME:$COUCHBASE_ADMIN_PASSWORD -v http://couchbase.1:8091/controller/addNode -d 'hostname=couchbase.3&user=$COUCHBASE_ADMIN_USERNAME&password=$COUCHBASE_ADMIN_PASSWORD&services=kv'

# Rebalance
curl -u $COUCHBASE_ADMIN_USERNAME:$COUCHBASE_ADMIN_PASSWORD -v http://couchbase.1:8091/controller/rebalance -d 'knownNodes=ns_1@couchbase.1,ns_1@couchbase.2,ns_1@couchbase.3'
