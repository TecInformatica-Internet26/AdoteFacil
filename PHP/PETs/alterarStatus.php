<?php
session_start();
require '../conexao.php';

if(!isset($_SESSION['usuario_id'])){
    die("Usuário não logado.");
}

$id_pet = $_POST['id_pet'];
$status = $_POST['status'];

$sql = "UPDATE pet SET statusPet = ? WHERE id_pet = ? AND id_cliente = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("sii", $status, $id_pet, $_SESSION['usuario_id']);
$stmt->execute();

// salva histórico
$sqlHistorico = "INSERT INTO historico (id_pet, id_cliente, status_pet)
                 VALUES (?, ?, ?)";
$stmtH = $conexao->prepare($sqlHistorico);
$stmtH->bind_param("iis", $id_pet, $_SESSION['usuario_id'], $status);
$stmtH->execute();

header("Location: ../Usuario/perfil.php");
exit;
?>