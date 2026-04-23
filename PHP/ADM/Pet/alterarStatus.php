<?php
include('../../conexao.php');

$id_pet = $_POST['id_pet'];
$status = $_POST['status'];

$sql = "UPDATE pet SET statusPet = '$status' WHERE id_pet = $id_pet";

if (mysqli_query($conexao, $sql)) {
    header("Location: consulta.php");
    exit;
} else {
    echo "Erro ao atualizar: " . mysqli_error($conexao);
}
?>
