<?php
// Test if we can create a socket
$port = 8082;
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($sock === false) {
    echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
} else {
    $result = socket_bind($sock, '127.0.0.1', $port);
    if ($result === false) {
        echo "socket_bind() failed: " . socket_strerror(socket_last_error($sock)) . "\n";
    } else {
        echo "Socket bind successful on 127.0.0.1:" . $port . "\n";
        socket_close($sock);
    }
}