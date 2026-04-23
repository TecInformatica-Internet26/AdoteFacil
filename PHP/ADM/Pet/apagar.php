<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('../../conexao.php');

// Verifica se o ID foi passado
if (!isset($_GET['id_pet'])) {
    die("ID não informado.");
}

$id = intval($_GET['id_pet']);

// Buscar a foto do pet
$sqlFoto = "SELECT foto FROM pet WHERE id_pet = $id LIMIT 1";
$resultFoto = mysqli_query($conexao, $sqlFoto);

if (mysqli_num_rows($resultFoto) === 0) {
    die("Pet não encontrado.");
}

$dados = mysqli_fetch_assoc($resultFoto);
$foto = $dados['foto'];

// 🔥 Primeiro apaga o histórico relacionado ao pet
$sqlDeleteHist = "DELETE FROM historico WHERE id_pet = $id";
mysqli_query($conexao, $sqlDeleteHist);

// Agora pode apagar o pet
$sqlDeletePet = "DELETE FROM pet WHERE id_pet = $id";

if (mysqli_query($conexao, $sqlDeletePet)) {

    // Se existir uma foto, apagar a imagem da pasta
    $caminhoFoto = "../../../IMG/usuario/" . $foto;

    if (!empty($foto) && file_exists($caminhoFoto)) {
        unlink($caminhoFoto);
    }

    // Redireciona de volta para a consulta
    header("Location: consulta.php?msg=apagado");
    exit();

} else {
    echo "Erro ao apagar o pet: " . mysqli_error($conexao);
}
?>
