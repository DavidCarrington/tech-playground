<?php

$redis = new Redis();
$redis->connect('redis.1');

echo "<li>Set Foo=Bar</li>";
var_dump($redis->set('Foo', 'Bar'));

echo "<li>Get Foo</li>";
var_dump($redis->get('Foo'));


echo "<li>Redis->keys('F*')</li>";
print_r($redis->keys('F*'));


echo "<li>Set 100 TEST* keys (in a pipeline, just for fun)</li>";
$pipe = $redis->pipeline();
for ($i = 0; $i < 100; $i++) {
    $pipe->set('TEST' . $i, 'Char' . $i);
}
print_r($pipe->exec());
$pipe->close();

echo "<li>Scan TEST* keys, COUNT 20</li>";
print_r($redis->scan($a, 'TEST*', 20));
echo "<li>Iterate...</li>";
print_r($redis->scan($a, 'TEST*', 20));
echo "<li>Iterate...</li>";
print_r($redis->scan($a, 'TEST*', 20));
echo "<li>Iterate...</li>";
print_r($redis->scan($a, 'TEST*', 20));
echo "<li>Iterate...</li>";
print_r($redis->scan($a, 'TEST*', 20));
