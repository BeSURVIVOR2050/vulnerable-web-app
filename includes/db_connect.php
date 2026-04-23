<?php
/**
 * Database connection for XAMPP (defaults) or Docker Compose (override via env).
 */
function db_connect(): mysqli
{
    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASSWORD');
    $pass = $pass === false ? '' : $pass;
    $name = getenv('DB_NAME') ?: 'secure_db';
    $port = (int) (getenv('DB_PORT') !== false ? getenv('DB_PORT') : 3307);

    $conn = mysqli_connect($host, $user, $pass, $name, $port);
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }
    return $conn;
}
