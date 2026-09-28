<?php

require_once "includes/verificar_login.php";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Sistema de Produtos</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <div class="card">

            <h1>📦 Sistema de Produtos</h1>

            <h2>
                Bem-vindo,
                <?php echo htmlspecialchars($_SESSION["usuario_nome"]); ?>!
            </h2>

            <p>
                Utilize o menu abaixo para gerenciar os produtos.
            </p>

            <div class="menu">

                <a class="btn" href="produtos/cadastrar.php">
                    ➕ Cadastrar Produto
                </a>

                <a class="btn" href="produtos/listar.php">
                    📋 Listar Produtos
                </a>

                <a class="btn btn-danger" href="logout.php">
                    🚪 Sair
                </a>

            </div>

        </div>

    </div>

</body>

</html>