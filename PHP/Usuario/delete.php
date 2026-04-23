<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Sessão expirada! Faça login novamente.'); window.location.href='../../Paginas/entrar.php';</script>";
    exit;
}

$id = $_SESSION['usuario_id'];

/* === 1. Buscar foto antes de excluir === */
$sqlFoto = "SELECT foto FROM cliente WHERE id_cliente = ?";
$stmtFoto = $conexao->prepare($sqlFoto);
$stmtFoto->bind_param("i", $id);
$stmtFoto->execute();
$resultFoto = $stmtFoto->get_result();

if ($resultFoto->num_rows > 0) {
    $foto = $resultFoto->fetch_assoc()['foto'];
    $caminhoFoto = "../../IMG/usuario/" . $foto;

    if (file_exists($caminhoFoto)) {
        unlink($caminhoFoto);
    }
}

/* === 2. Deletar histórico do cliente === */
$sqlHistorico = "DELETE FROM historico WHERE id_cliente = ?";
$stmtHistorico = $conexao->prepare($sqlHistorico);
$stmtHistorico->bind_param("i", $id);
$stmtHistorico->execute();

/* === 3. Deletar Pets do cliente === */
$sqlPet = "DELETE FROM pet WHERE id_cliente = ?";
$stmtPet = $conexao->prepare($sqlPet);
$stmtPet->bind_param("i", $id);
$stmtPet->execute();

/* === 4. Deletar usuário === */
$sqlCliente = "DELETE FROM cliente WHERE id_cliente = ?";
$stmtCliente = $conexao->prepare($sqlCliente);
$stmtCliente->bind_param("i", $id);

if ($stmtCliente->execute()) {
    session_unset();
    session_destroy();
    echo "<script>alert('Conta deletada com sucesso.'); window.location.href='../../Paginas/entrar.php';</script>";
    exit;
} else {
    echo "<script>alert('Erro ao deletar conta.'); window.location.href='../Paginas/perfil.php';</script>";
    exit;
}
?>
