<?php

/**
 * An experiment to prove how flakey Memcached is.
 * If any server is offline then you lose the ability to read AND WRITE part of your data.
 *
 * Result: server offline doesn't prevent PHP attempting to use it. Client side hash-based "load balancing"
 * is used and has no intelligence to take failing servers out of the pool.
 */

$cache = new Memcached();
$cache->addServers([
    ['memcached.1', 11211],
    ['memcached.2', 11211],
    ['memcached.3', 11211],
]);
echo '<pre>';

for ($i = 0; $i < 50; $i++) {
    echo 'counter' . $i, ': ', $cache->set('counter' . $i, 1) ? 'OK' : '<font color=red>Failed</font>', "\n";
}
