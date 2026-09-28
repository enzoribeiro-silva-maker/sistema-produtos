<?php

session_start();

include "includes/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($email === "" || $senha === "") {

        $erro = "Preencha todos os campos.";

    } else {

        $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ? LIMIT 1";

        $stmt = $conexao->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 1) {

                $usuario = $resultado->fetch_assoc();

                if ($senha === $usuario["senha"]) {

                    $_SESSION["usuario_id"] = $usuario["id"];
                    $_SESSION["usuario_nome"] = $usuario["nome"];
                    $_SESSION["usuario_email"] = $usuario["email"];

                    header("Location: dashboard.php");
                    exit;

                } else {

                    $erro = "Senha incorreta.";

                }

            } else {

                $erro = "E-mail não encontrado.";

            }

            $stmt->close();

        } else {

            $erro = "Erro ao realizar a consulta no banco de dados.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sistema de Produtos</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="login">

        <div class="card">

            <div class="text-center">

                <h1>📦 Sistema de Produtos</h1>

                <p>Faça login para continuar</p>

            </div>

            <?php if ($erro !== ""): ?>

                <p class="mensagem">
                    <?php echo htmlspecialchars($erro); ?>
                </p>

            <?php endif; ?>

            <form method="POST">

                <label>E-mail</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Digite seu e-mail"
                    required
                >

                <br><br>

                <label>Senha</label>

                <input
                    type="password"
                    name="senha"
                    placeholder="Digite sua senha"
                    required
                >

                <button type="submit">
                    Entrar
                </button>

            </form>

        </div>

    </div>

</body>

</html>