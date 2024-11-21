<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "registros");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
    // Recebe os dados do formulário
    $cliente = $_POST["cliente"];
    $cidade = $_POST["cidade"];
    $estado = $_POST["estado"];
    $modelo = $_POST["modelo"];
    $nserie = $_POST["nserie"];
    $calibragem = $_POST["calibragem"];

    // Insere os dados na tabela
    $sql = "INSERT INTO clientes (cliente, cidade, estado, modelo, nserie, calibragem) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $cliente, $cidade, $estado, $modelo, $nserie, $calibragem);

    if ($stmt->execute()) {
        echo "Novo registro adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar registro: " . $conn->error;
    }

    $stmt->close();
}

// Exibe os registros existentes na tabela
$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./files/reset.css">
    <link rel="stylesheet" href="./files/tables.css">
    <title>Registros Personalizados de Isadora</title>
</head>
<body>
<header>
    <h1>Utilitários</h1>
</header>
<main>
    <h2>Registros Personalizados</h2>

    <!-- Formulário para adicionar novo registro -->
    <form method="POST" action="index.php">
        <table>
            <tr>
                <th>Cliente</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Modelo</th>
                <th>Nº de Série</th>
                <th>Última Calibragem</th>
            </tr>
            <tr>
                <td><input type="text" name="cliente" required></td>
                <td><input type="text" name="cidade" required></td>
                <td><input type="text" name="estado" required></td>
                <td><input type="text" name="modelo" required></td>
                <td><input type="text" name="nserie" required></td>
                <td><input type="text" name="calibragem" required></td>
            </tr>
        </table>
        <button type="submit" name="add">Registrar</button>
    </form>

    <h3>Registros Existentes</h3>
    <table>
        <tr>
            <th>Cliente</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th>Modelo</th>
            <th>Nº de Série</th>
            <th>Última Calibragem</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["cliente"]; ?></td>
                    <td><?= $row["cidade"]; ?></td>
                    <td><?= $row["estado"]; ?></td>
                    <td><?= $row["modelo"]; ?></td>
                    <td><?= $row["nserie"]; ?></td>
                    <td><?= $row["calibragem"]; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Nenhum registro encontrado.</td>
            </tr>
        <?php endif; ?>
    </table>
    <br>
    <a class="back" href="index.php">Voltar</a>
</main>
</body>
</html>