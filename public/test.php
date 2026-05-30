<?php
require_once '../app/config/config.php';
require_once '../app/config/Database.php';

$database = new Database();

$connection = $database->connect();

if ($connection) {
    echo "Database connected successfully";
}