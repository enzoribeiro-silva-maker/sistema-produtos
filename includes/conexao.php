<?php

$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "sistema_produtos";
$porta = 3306;

$conexao = new mysqli(
    $servidor,
    $usuario,
    $senha,
    $banco,
    $porta
);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8mb4");

?>