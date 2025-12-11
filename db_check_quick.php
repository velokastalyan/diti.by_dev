<?php
$host = 'db';
$user = 'root';
$pass = 'rootpassword'; // Взято из docker-compose
$db   = 'user2160086_timistkas_sportmax';

echo 'Testing connection to host: ' . $host . ' with user: ' . $user . PHP_EOL;
$mysqli = @new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    echo '[ERROR] Connect Error: ' . $mysqli->connect_error . PHP_EOL;
} else {
    echo '[SUCCESS] Connected to database: ' . $db . PHP_EOL;
    echo 'MySQL Server info: ' . $mysqli->host_info . PHP_EOL;
    
    // Проверка кодировки
    echo 'Charset: ' . $mysqli->character_set_name() . PHP_EOL;
}

