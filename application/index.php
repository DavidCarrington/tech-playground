<?php

use Couchbase\PasswordAuthenticator;

echo "<pre>";
$bucketName = "my_bucket";

// Establish username and password for bucket-access
$authenticator = new PasswordAuthenticator();
$authenticator->username($_ENV['COUCHBASE_ADMIN_USERNAME'])->password($_ENV['COUCHBASE_ADMIN_PASSWORD']);

// Connect to Couchbase Server - using address of a KV (data) node
$cluster = new CouchbaseCluster("couchbase://couchbase.1,couchbase.2,couchbase.3?detailed_errcodes=true");

// Authenticate, then open bucket
$cluster->authenticate($authenticator);
$bucket = $cluster->openBucket($bucketName);

$key = substr(md5(microtime(true)), 0, 2);

echo "Get\n";
try {
    $result = $bucket->get($key);
} catch (\Couchbase\Exception $ex) {
    if ($ex->getCode() == COUCHBASE_KEY_ENOENT) {
        echo "Not found (BAU)\n";
    } else {
        throw $ex;
    }
}
print_r($result);

// Store a document
echo "Storing u:king_arthur\n";
$result = $bucket->upsert($key, array(
    "email" => "kingarthur@couchbase.com",
    "interests" => array("African Swallows")
));

print_r($result);

// Retrieve a document
echo "Getting back u:king_arthur\n";
$result = $bucket->get($key);
print_r($result->value);

// Replace a document
echo "Replacing u:king_arthur\n";
$doc = $result->value;
array_push($doc->interests, 'PHP 7');
$bucket->replace($key, $doc);
print_r($result);

/*
echo "Creating primary index\n";
// Before issuing a N1QL Query, ensure that there is
// is actually a primary index.
try {
    // Do not override default name; fail if it already exists, and wait for completion
    $bucket->manager()->createN1qlPrimaryIndex('', false, false);
    echo "Primary index has been created\n";
} catch (CouchbaseException $e) {
    printf("Couldn't create index. Maybe it already exists? (code: %d)\n", $e->getCode());
}

// Query with parameters
$query = CouchbaseN1qlQuery::fromString("SELECT * FROM `$bucketName` WHERE \$p IN interests");
$query->namedParams(array("p" => "African Swallows"));
echo "Parameterized query:\n";
print_r($query);
$rows = $bucket->query($query);
echo "Results:\n";
print_r($rows);
*/