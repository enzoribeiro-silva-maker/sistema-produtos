<?php
session_start();
require_once __DIR__ . "/includes/conexao.php";

$erro = "";
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmacao = $_POST["confirmacao"] ?? "";

    if ($nome === "" || $email === "" || $senha === "" || $confirmacao === "") {
        $erro = "Preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Informe um e-mail válido.";
    } elseif (strlen($senha) < 8) {
        $erro = "A senha deve ter pelo menos 8 caracteres.";
    } elseif ($senha !== $confirmacao) {
        $erro = "As senhas não coincidem.";
    } else {
        try {
            $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $erro = "Este e-mail já está cadastrado.";
                $stmt->close();
            } else {
                $stmt->close();
                $hash = password_hash($senha, PASSWORD_DEFAULT);

                $stmt = $conexao->prepare(
                    "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)"
                );
                $stmt->bind_param("sss", $nome, $email, $hash);
                $stmt->execute();
                $stmt->close();
                $sucesso = true;
            }
        } catch (mysqli_sql_exception $e) {
            $erro = "Não foi possível criar a conta. Verifique os dados e tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar conta | Sistema de Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="login">
    <section class="card">
        <h1 class="text-center">Sistema de Produtos</h1>
        <h2>Criar conta</h2>

        <?php if ($erro !== ""): ?>
            <p class="mensagem mensagem-erro" role="alert"><?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?></p>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <p class="mensagem">Conta criada com sucesso. Você já pode entrar.</p>
            <p><a href="index.php">Voltar ao login</a></p>
        <?php else: ?>
            <form method="POST" action="criar_conta.php">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" maxlength="100" autocomplete="name" required>

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" maxlength="190" autocomplete="email" required>

                <label for="senha">Senha (mínimo 8 caracteres)</label>
                <input type="password" id="senha" name="senha" minlength="8" autocomplete="new-password" required>

                <label for="confirmacao">Confirmar senha</label>
                <input type="password" id="confirmacao" name="confirmacao" minlength="8" autocomplete="new-password" required>

                <button type="submit">Criar conta</button>
            </form>
            <p class="login-links"><a href="index.php">Voltar ao login</a></p>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
