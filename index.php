
<?php

session_start();

require_once __DIR__ . "/includes/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senhaDigitada = $_POST["senha"] ?? "";

    if ($email === "" || $senhaDigitada === "") {

        $erro = "Preencha todos os campos.";

    } else {

        $sql = "SELECT id, nome, email, senha
                FROM usuarios
                WHERE email = ?
                LIMIT 1";

        $stmt = $conexao->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows === 1) {

                $usuario = $resultado->fetch_assoc();
                $senhaBanco = $usuario["senha"];

                // Verifica senhas que já estão armazenadas como hash.
                $senhaValida = password_verify(
                    $senhaDigitada,
                    $senhaBanco
                );

                // Compatibilidade temporária com senhas antigas
                // que ainda estejam armazenadas em texto simples.
                if (
                    !$senhaValida &&
                    hash_equals((string) $senhaBanco, (string) $senhaDigitada)
                ) {

                    $novoHash = password_hash(
                        $senhaDigitada,
                        PASSWORD_DEFAULT
                    );

                    if ($novoHash !== false) {

                        $atualizacao = $conexao->prepare(
                            "UPDATE usuarios SET senha = ? WHERE id = ?"
                        );

                        if ($atualizacao) {

                            $idUsuario = (int) $usuario["id"];

                            $atualizacao->bind_param(
                                "si",
                                $novoHash,
                                $idUsuario
                            );

                            if ($atualizacao->execute()) {
                                $senhaValida = true;
                            }

                            $atualizacao->close();
                        }
                    }
                }

                if ($senhaValida) {

                    // Renova o identificador da sessão após o login.
                    session_regenerate_id(true);

                    $_SESSION["usuario_id"] = $usuario["id"];
                    $_SESSION["usuario_nome"] = $usuario["nome"];
                    $_SESSION["usuario_email"] = $usuario["email"];

                    header("Location: dashboard.php");
                    exit;

                } else {

                    $erro = "Senha incorreta.";

                }

            } else {

                $erro = "E-mail ou senha incorretos.";

            }

            $stmt->close();

        } else {

            $erro = "Não foi possível realizar o login. Tente novamente.";

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

                <h1>Sistema de Produtos</h1>

                <p>Faça login para continuar</p>

            </div>

            <?php if ($erro !== ""): ?>

                <p class="mensagem">
                    <?= htmlspecialchars(
                        $erro,
                        ENT_QUOTES,
                        "UTF-8"
                    ); ?>
                </p>

            <?php endif; ?>

            <form method="POST" action="">

                <label for="email">E-mail</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Digite seu e-mail"
                    autocomplete="username"
                    required
                >

                <br><br>

                <label for="senha">Senha</label>

                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="Digite sua senha"
                    autocomplete="current-password"
                    required
                >

                <button type="submit">
                    Entrar
                </button>

            </form>

            <div class="login-links">
                <a href="recuperar_senha.php">Esqueceu a senha?</a>
                <a href="criar_conta.php">Criar conta</a>
            </div>

        </div>

    </div>

</body>

</html>
