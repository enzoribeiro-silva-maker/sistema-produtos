
<?php
session_start();

require_once "includes/conexao.php";
require_once "includes/verificar_login.php";

// Nome do usuário conectado
$nomeUsuario = $_SESSION["usuario"] ?? "Usuário";
$totalProdutos = 0;

// Consulta a quantidade de produtos cadastrados
try {
    $resultado = $conexao->query("SELECT COUNT(*) AS total FROM produtos");

    if ($resultado) {
        $dados = $resultado->fetch_assoc();
        $totalProdutos = (int) ($dados["total"] ?? 0);
    }
} catch (mysqli_sql_exception $e) {
    $totalProdutos = 0;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel - Sistema de Produtos</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .dashboard-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }

        .dashboard-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .dashboard-card {
            padding: 24px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
        }

        .dashboard-card h2 {
            margin-top: 0;
        }

        .dashboard-card a,
        .dashboard-actions a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 14px;
            border-radius: 6px;
            text-decoration: none;
            background: #174ea6;
            color: white;
        }

        .dashboard-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .dashboard-total {
            font-size: 32px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<main class="dashboard-container">

    <header class="dashboard-header">
        <div>
            <h1>Sistema de Produtos</h1>
            <p>
                Bem-vindo(a),
                <?= htmlspecialchars($nomeUsuario, ENT_QUOTES, "UTF-8") ?>!
            </p>
        </div>

        <nav class="dashboard-actions" aria-label="Ações da conta">
            <a href="alterar_senha.php">Alterar senha</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <section class="dashboard-menu">

        <article class="dashboard-card">
            <h2>Produtos cadastrados</h2>
            <p class="dashboard-total">
                <?= $totalProdutos ?>
            </p>
            <a href="produtos/listar.php">Ver produtos</a>
        </article>

        <article class="dashboard-card">
            <h2>Cadastrar produto</h2>
            <p>Adicionar um novo produto ao sistema.</p>
            <a href="produtos/cadastrar.php">Cadastrar produto</a>
        </article>

        <article class="dashboard-card">
            <h2>Consultar produtos</h2>
            <p>Consultar os registros já cadastrados.</p>
            <a href="produtos/listar.php">Consultar</a>
        </article>

        <article class="dashboard-card">
            <h2>Minha conta</h2>
            <p>Atualizar sua senha de acesso.</p>
            <a href="alterar_senha.php">Alterar senha</a>
        </article>

    </section>

</main>

</body>
</html>
