<?php

require_once "../includes/verificar_login.php";
require_once "../includes/conexao.php";

if (!isset($_GET["id"])) {
    header("Location: listar.php");
    exit;
}

$id = $_GET["id"];

$sql = "SELECT * FROM produtos WHERE id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    echo "Produto não encontrado.";
    exit;
}

$produto = $resultado->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $codigo = $_POST["codigo"];
    $categoria = $_POST["categoria"];
    $preco = $_POST["preco"];
    $estoque = $_POST["estoque"];

    $sql = "UPDATE produtos SET
            nome = ?,
            codigo = ?,
            categoria = ?,
            preco = ?,
            estoque = ?
            WHERE id = ?";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "sssdii",
        $nome,
        $codigo,
        $categoria,
        $preco,
        $estoque,
        $id
    );

    if ($stmt->execute()) {
        header("Location: listar.php");
        exit;
    } else {
        echo "Erro ao atualizar o produto.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
</head>

<body>

    <h1>Editar Produto</h1>

    <form method="POST">

        <label>Nome do produto:</label><br>
        <input
            type="text"
            name="nome"
            value="<?php echo htmlspecialchars($produto["nome"]); ?>"
            required
        >

        <br><br>

        <label>Código:</label><br>
        <input
            type="text"
            name="codigo"
            value="<?php echo htmlspecialchars($produto["codigo"]); ?>"
            required
        >

        <br><br>

        <label>Categoria:</label><br>
        <input
            type="text"
            name="categoria"
            value="<?php echo htmlspecialchars($produto["categoria"]); ?>"
            required
        >

        <br><br>

        <label>Preço:</label><br>
        <input
            type="number"
            name="preco"
            step="0.01"
            min="0"
            value="<?php echo $produto["preco"]; ?>"
            required
        >

        <br><br>

        <label>Estoque:</label><br>
        <input
            type="number"
            name="estoque"
            min="0"
            value="<?php echo $produto["estoque"]; ?>"
            required
        >

        <br><br>

        <button type="submit">Salvar Alterações</button>

    </form>

    <br>

    <a href="listar.php">Voltar para Produtos</a>

</body>

</html>