```php
<?php

require_once "../includes/verificar_login.php";
require_once "../includes/conexao.php";

$sql = "SELECT * FROM produtos ORDER BY id DESC";

$resultado = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Produtos Cadastrados</title>

    <link rel="stylesheet" href="../style.css">

</head>

<body>

    <div class="container">

        <div class="card">

            <h1>📋 Produtos Cadastrados</h1>

            <p>
                Lista de produtos registrados no sistema.
            </p>

            <br>

            <a class="btn" href="cadastrar.php">
                ➕ Cadastrar Produto
            </a>

            <table>

                <tr>

                    <th>ID</th>

                    <th>Nome</th>

                    <th>Código</th>

                    <th>Categoria</th>

                    <th>Preço</th>

                    <th>Estoque</th>

                    <th>Ações</th>

                </tr>

                <?php if ($resultado->num_rows > 0): ?>

                    <?php while ($produto = $resultado->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php echo $produto["id"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($produto["nome"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($produto["codigo"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($produto["categoria"]); ?>
                            </td>

                            <td>
                                R$
                                <?php
                                echo number_format(
                                    $produto["preco"],
                                    2,
                                    ",",
                                    "."
                                );
                                ?>
                            </td>

                            <td>
                                <?php echo $produto["estoque"]; ?>
                            </td>

                            <td>

                                <a
                                    class="btn"
                                    href="editar.php?id=<?php echo $produto["id"]; ?>"
                                >
                                    Editar
                                </a>

                                <a
                                    class="btn btn-danger"
                                    href="excluir.php?id=<?php echo $produto["id"]; ?>"
                                    onclick="return confirm('Deseja realmente excluir este produto?');"
                                >
                                    Excluir
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="7" class="text-center">

                            Nenhum produto cadastrado.

                        </td>

                    </tr>

                <?php endif; ?>

            </table>

            <br>

            <a
                class="btn btn-secondary"
                href="../dashboard.php"
            >
                ← Voltar para o Dashboard
            </a>

        </div>

    </div>

</body>

</html>
```
