
<?php
session_start();
require_once "includes/conexao.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $atual = $_POST["senha_atual"] ?? "";
    $nova = $_POST["nova_senha"] ?? "";
    $confirmacao = $_POST["confirmacao"] ?? "";

    if ($atual === "" || $nova === "" || $confirmacao === "") {
        $erro = "Preencha todos os campos.";
    } elseif (strlen($nova) < 8) {
        $erro = "A nova senha deve ter pelo menos 8 caracteres.";
    } elseif ($nova !== $confirmacao) {
        $erro = "A nova senha e a confirmação não coincidem.";
    } elseif ($atual === $nova) {
        $erro = "Escolha uma senha diferente da atual.";
    } else {
        $id = (int) $_SESSION["usuario_id"];

        $stmt = $conn->prepare(
            "SELECT senha FROM users WHERE id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($hashAtual);

        if ($stmt->fetch()) {
            $stmt->close();

            if (!password_verify($atual, $hashAtual)) {
                $erro = "A senha atual está incorreta.";
            } else {
                $novoHash = password_hash($nova, PASSWORD_DEFAULT);

                $stmt = $conn->prepare(
                    "UPDATE users SET senha = ? WHERE id = ?"
                );
                $stmt->bind_param("si", $novoHash, $id);

                if ($stmt->execute()) {
                    $sucesso = "Senha alterada com sucesso.";
                } else {
                    $erro = "Não foi possível alterar a senha.";
                }

                $stmt->close();
            }
        } else {
            $stmt->close();
            $erro = "Usuário não encontrado.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alterar senha - Sistema de Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <h1>Alterar senha</h1>

        <?php if ($erro !== ""): ?>
            <p role="alert">
                <?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <?php if ($sucesso !== ""): ?>
            <p>
                <?= htmlspecialchars($sucesso, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="alterar_senha.php">
            <label for="senha_atual">Senha atual</label>
            <input
                type="password"
                id="senha_atual"
                name="senha_atual"
                autocomplete="current-password"
                required
            >

            <label for="nova_senha">Nova senha</label>
            <input
                type="password"
                id="nova_senha"
                name="nova_senha"
                minlength="8"
                autocomplete="new-password"
                required
            >

            <label for="confirmacao">Confirmar nova senha</label>
            <input
                type="password"
                id="confirmacao"
                name="confirmacao"
                minlength="8"
                autocomplete="new-password"
                required
            >

            <button type="submit">Alterar senha</button>
        </form>

        <p><a href="dashboard.php">Voltar ao painel</a></p>
    </main>
</body>
</html>
