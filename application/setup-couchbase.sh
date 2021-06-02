# Set up server 1
curl -v http://couchbase.one:8091/pools/default -d memoryQuota=256 -d indexMemoryQuota=256
curl -v http://couchbase.one:8091/node/controller/setupServices -d services=kv%2cn1ql%2Cindex
curl -v http://couchbase.one:8091/settings/web -d port=8091 -d username=$COUCHBASE_ADMIN_USERNAME -d password=$COUCHBASE_ADMIN_PASSWORD
curl -v -u $COUCHBASE_ADMIN_USERNAME:$COUCHBASE_ADMIN_PASSWORD http://couchbase.one:8091/pools/default/buckets -d name=my_bucket -d bucketType=couchbase -d ramQuotaMB=128 -d authType=sasl -d saslPassword=my_bucket_PASSWORD
curl -v -u $COUCHBASE_ADMIN_USERNAME:$COUCHBASE_ADMIN_PASSWORD http://couchbase.one:8091/node/controller/rename -d hostname=couchbase.one

# Add server 2 to cluster, and rebalance
curl -u $COUCHBASE_ADMIN_USERNAME:$COUCHBASE_ADMIN_PASSWORD -v http://couchbase.one:8091/controller/addNode -d 'hostname=couchbase.two&user=$COUCHBASE_ADMIN_USERNAME&password=$COUCHBASE_ADMIN_PASSWORD&services=kv'
curl -u $COUCHBASE_ADMIN_USERNAME:$COUCHBASE_ADMIN_PASSWORD -v http://couchbase.one:8091/controller/rebalance -d 'knownNodes=ns_1@couchbase.one,ns_1@couchbase.two'
