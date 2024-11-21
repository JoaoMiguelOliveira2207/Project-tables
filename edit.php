<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "registros");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se um ID foi passado na URL para buscar os dados do cliente
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Obtém os dados do cliente para preenchê-los no formulário
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
    } else {
        echo "Registro não encontrado.";
        exit();
    }
    $stmt->close();
}

// Processa o formulário de atualização
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $cliente = $_POST["cliente"];
    $cidade = $_POST["cidade"];
    $estado = $_POST["estado"];
    $modelo = $_POST["modelo"];
    $nserie = $_POST["nserie"];
    $calibragem = $_POST["calibragem"];

    // Atualiza os dados do cliente no banco de dados
    $sql = "UPDATE clientes SET cliente = ?, cidade = ?, estado = ?, modelo = ?, nserie = ?, calibragem = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $cliente, $cidade, $estado, $modelo, $nserie, $calibragem, $id);

    if ($stmt->execute()) {
        echo "Registro atualizado com sucesso!";
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao atualizar registro: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="files/reset.css">
    <link rel="stylesheet" href="files/edit.css">
    <script>
    function confirmDelete() {
        if (confirm("Você tem certeza que deseja deletar este registro?")) {
            window.location.href = "delete.php?id=<?= $cliente['id']; ?>"; // Redireciona para o arquivo de deletar
        }
    }
    </script>
</head>
<body>
    <header>
        <h1>Editar Registro</h1>
    </header>
    <main>
        <form method="POST" action="edit.php">
            <input type="hidden" name="id" value="<?= $cliente['id']; ?>">
            <label>Cliente:
                <input type="text" name="cliente" value="<?= $cliente['cliente']; ?>" required>
            </label>
            <label>Cidade:
                <input type="text" name="cidade" value="<?= $cliente['cidade']; ?>" required>
            </label>
            <label>Estado:
                <input type="text" name="estado" value="<?= $cliente['estado']; ?>" required>
            </label>
            <label>Modelo:
                <input type="text" name="modelo" value="<?= $cliente['modelo']; ?>" required>
            </label>
            <label>Nº de Série:
                <input type="text" name="nserie" value="<?= $cliente['nserie']; ?>" required>
            </label>
            <label>Última Calibragem:
                <input type="text" name="calibragem" value="<?= $cliente['calibragem']; ?>" required>
            </label>
            <button type="submit">Salvar Alterações</button>
            <button type="button" onclick="confirmDelete()">Deletar Registro</button>
        </form>
    </main>
</body>
</html>
