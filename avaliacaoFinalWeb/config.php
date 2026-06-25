<?php
// Dados de conexão — em um projeto real, guarde em arquivo separado (ex: config.php)
$host   = "localhost";
$user   = "root";
$pass = "";
$dbname = "jogodigitacao";
$port = 3307;

// Estabelece a conexão
$conn = mysqli_connect($host, $user, $pass, $dbname, $port);

// Verifica se houve erro
if (mysqli_connect_error()) {
    die("Erro de conexão: " . mysqli_connect_error());
}

// Define o charset para utf8mb4 (suporte a acentos e emojis)
mysqli_set_charset($conn, "utf8mb4");

?>