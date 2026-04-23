<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('../../conexao.php');

if (!isset($_GET['id'])) {
    die("ID não informado.");
}

$id = intval($_GET['id']);

// Buscar foto do cliente
$sqlFoto = "SELECT foto FROM cliente WHERE id_cliente = $id LIMIT 1";
$resultFoto = mysqli_query($conexao, $sqlFoto);

if (mysqli_num_rows($resultFoto) === 0) {
    die("Cliente não encontrado.");
}

$dados = mysqli_fetch_assoc($resultFoto);
$fotoCliente = $dados['foto'];


// 🔵 1 — Buscar todos os pets do cliente
$sqlPets = "SELECT id_pet, foto FROM pet WHERE id_cliente = $id";
$resultPets = mysqli_query($conexao, $sqlPets);

// Guardar pets para apagar fotos depois
$pets = [];
while ($row = mysqli_fetch_assoc($resultPets)) {
    $pets[] = $row;
}


// 🔵 2 — Apagar histórico de todos os pets do cliente
$sqlDeleteHistorico = "DELETE FROM historico WHERE id_pet IN (SELECT id_pet FROM pet WHERE id_cliente = $id)";
mysqli_query($conexao, $sqlDeleteHistorico);


// 🔵 3 — Apagar pets do cliente
$sqlDeletePets = "DELETE FROM pet WHERE id_cliente = $id";
mysqli_query($conexao, $sqlDeletePets);


// 🔵 4 — Apagar cliente
$sqlDeleteCliente = "DELETE FROM cliente WHERE id_cliente = $id";

if (mysqli_query($conexao, $sqlDeleteCliente)) {

    // Apagar foto do cliente
    if (!empty($fotoCliente) && file_exists("../../../IMG/usuario/" . $fotoCliente)) {
        unlink("../../../IMG/usuario/" . $fotoCliente);
    }

    // Apagar fotos dos pets
    foreach ($pets as $pet) {
        if (!empty($pet['foto']) && file_exists("../../../IMG/usuario/" . $pet['foto'])) {
            unlink("../../../IMG/usuario/" . $pet['foto']);
        }
    }

    header("Location: consulta.php?msg=apagado");
    exit();
    
} else {
    echo "Erro ao apagar cliente: " . mysqli_error($conexao);
}
?>
