<?php

require_once "../includes/verificar_login.php";
require_once "../includes/conexao.php";

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $codigo = trim($_POST["codigo"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $preco = $_POST["preco"] ?? "";
    $estoque = $_POST["estoque"] ?? "";

    // Verifica se o código já existe
    $consulta = "SELECT id FROM produtos WHERE codigo = ? LIMIT 1";

    $stmtConsulta = $conexao->prepare($consulta);
    $stmtConsulta->bind_param("s", $codigo);
    $stmtConsulta->execute();

    $resultado = $stmtConsulta->get_result();

    if ($resultado->num_rows > 0) {

        $mensagem = "Código já cadastrado! Utilize outro código para o produto.";
        $tipoMensagem = "erro";

    } else {

        $sql = "INSERT INTO produtos
                (nome, codigo, categoria, preco, estoque)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "sssdi",
            $nome,
            $codigo,
            $categoria,
            $preco,
            $estoque
        );

        try {

            if ($stmt->execute()) {

                $mensagem = "Produto cadastrado com sucesso!";
                $tipoMensagem = "sucesso";

            } else {

                $mensagem = "Não foi possível cadastrar o produto.";
                $tipoMensagem = "erro";
            }

        } catch (mysqli_sql_exception $e) {

            $mensagem = "Não foi possível cadastrar o produto. Verifique os dados informados.";
            $tipoMensagem = "erro";
        }

        $stmt->close();
    }

    $stmtConsulta->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Produto</title>

    <link rel="stylesheet" href="../style.css">

</head>

<body>

    <div class="container">

        <div class="card">

            <h1>➕ Cadastrar Produto</h1>

            <?php if ($mensagem != ""): ?>

                <p class="mensagem <?php echo $tipoMensagem; ?>">
                    <?php echo htmlspecialchars($mensagem); ?>
                </p>

            <?php endif; ?>

            <form method="POST">

                <label>Nome do produto</label>

                <input
                    type="text"
                    name="nome"
                    required
                >

                <br><br>

                <label>Código</label>

                <input
                    type="text"
                    name="codigo"
                    required
                >

                <br><br>

                <label>Categoria</label>

                <input
                    type="text"
                    name="categoria"
                    required
                >

                <br><br>

                <label>Preço</label>

                <input
                    type="number"
                    name="preco"
                    step="0.01"
                    min="0"
                    required
                >

                <br><br>

                <label>Estoque</label>

                <input
                    type="number"
                    name="estoque"
                    min="0"
                    required
                >

                <br>

                <button type="submit">
                    Cadastrar Produto
                </button>

                <a class="btn btn-secondary" href="../dashboard.php">
                    Voltar
                </a>

            </form>

        </div>

    </div>

</body>

</html>