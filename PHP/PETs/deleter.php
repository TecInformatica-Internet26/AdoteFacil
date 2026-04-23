<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM pet WHERE id = :id";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([':id' => $id])) {
        echo "Usuário excluído com sucesso!";
    } else {
        echo "Erro ao excluir o usuário.";
    }

    echo "<br><a href='index.html'>Voltar para página Inicial</a>";
} else {
    echo "ID não fornecido.";
}
header("Location: editando.php");
exit;

?>