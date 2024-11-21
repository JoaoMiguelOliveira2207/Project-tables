<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "registros");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se um ID foi passado na URL para deletar o registro
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Deleta o registro do banco de dados
    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Registro deletado com sucesso!";
        header("Location: index.php"); // Redireciona de volta para a página inicial
        exit();
    } else {
        echo "Erro ao deletar registro: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>
