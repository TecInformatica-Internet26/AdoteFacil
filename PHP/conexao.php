<?php
$servidor = "localhost";
$usuario  = "root";
$senha    = "root"; 
$dbname   = "adotefacil";

$conexao = mysqli_connect($servidor, $usuario, $senha, $dbname);

if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");
?>