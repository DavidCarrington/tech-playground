<?php

use Couchbase\PasswordAuthenticator;

echo "<pre>";
$bucketName = "my_bucket";

// Establish username and password for bucket-access
$authenticator = new PasswordAuthenticator();
$authenticator->username($_ENV['COUCHBASE_ADMIN_USERNAME'])->password($_ENV['COUCHBASE_ADMIN_PASSWORD']);

// Connect to Couchbase Server - using address of a KV (data) node
$cluster = new CouchbaseCluster("couchbase://couchbase.one");

// Authenticate, then open bucket
$cluster->authenticate($authenticator);
$bucket = $cluster->openBucket($bucketName);

// Store a document
echo "Storing u:king_arthur\n";
$result = $bucket->upsert('u:king_arthur', array(
    "email" => "kingarthur@couchbase.com",
    "interests" => array("African Swallows")
));

print_r($result);

// Retrieve a document
echo "Getting back u:king_arthur\n";
$result = $bucket->get("u:king_arthur");
print_r($result->value);

// Replace a document
echo "Replacing u:king_arthur\n";
$doc = $result->value;
array_push($doc->interests, 'PHP 7');
$bucket->replace("u:king_arthur", $doc);
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