
<?php
session_start();
require_once "includes/conexao.php";

$erro = "";
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmacao = $_POST["confirmacao"] ?? "";

    if ($usuario === "" || $senha === "" || $confirmacao === "") {
        $erro = "Preencha todos os campos.";
    } elseif (strlen($senha) < 8) {
        $erro = "A senha deve ter pelo menos 8 caracteres.";
    } elseif ($senha !== $confirmacao) {
        $erro = "As senhas não coincidem.";
    } else {
        try {
            $stmt = $conn->prepare(
                "SELECT id FROM users WHERE usuario = ? LIMIT 1"
            );
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $erro = "Esse usuário já está cadastrado.";
                $stmt->close();
            } else {
                $stmt->close();

                $hash = password_hash($senha, PASSWORD_DEFAULT);

                $stmt = $conn->prepare(
                    "INSERT INTO users (usuario, senha) VALUES (?, ?)"
                );
                $stmt->bind_param("ss", $usuario, $hash);
                $stmt->execute();
                $stmt->close();

                $sucesso = true;
            }
        } catch (mysqli_sql_exception $e) {
            $erro = "Não foi possível criar a conta. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar conta - Sistema de Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <h1>Criar conta</h1>

        <?php if ($erro !== ""): ?>
            <p role="alert">
                <?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <p>Conta criada com sucesso! Agora você pode entrar.</p>
            <p><a href="index.php">Voltar ao login</a></p>
        <?php else: ?>
            <form method="POST" action="criar_conta.php">
                <label for="usuario">Usuário</label>
                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    maxlength="100"
                    autocomplete="username"
                    required
                >

                <label for="senha">Senha (mínimo 8 caracteres)</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

                <label for="confirmacao">Confirmar senha</label>
                <input
                    type="password"
                    id="confirmacao"
                    name="confirmacao"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

                <button type="submit">Criar conta</button>
            </form>

            <p><a href="index.php">Voltar ao login</a></p>
        <?php endif; ?>
    </main>
</body>
</html>
