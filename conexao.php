<?php
require_once __DIR__ . "/config.php";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (mysqli_connect_error()) {
    die("Erro de conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
