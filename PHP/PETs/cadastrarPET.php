<?php
session_start();
require '../conexao.php';

// usa a sessão correta
if (!isset($_SESSION['usuario_id'])) {
    die("Erro: cliente não está logado.");
}

$id_cliente = $_SESSION['usuario_id'];

// Recebe os dados do formulário
$nome = $_POST['nome'];
$genero = $_POST['genero'];
$idade = $_POST['idade'];
$especie = $_POST['especie'];
$porte = $_POST['porte'];
$raca = $_POST['raca'];
$situacao = $_POST['situacao'];

//------------------------------PROCESSANDO IMAGEM-------------------------------------------
// Pasta onde as imagens serão salvas
$pastaDestino = "../../IMG/adote/";

// Cria a pasta se não existir
if (!file_exists($pastaDestino)) {
    mkdir($pastaDestino, 0755, true);
}

$mensagem = "";
$caminhoImagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Gera nome único para a imagem
    $nomeImagem = uniqid() . "_" . basename($_FILES["foto"]["name"]);
    $caminhoFinal = $pastaDestino . $nomeImagem;
    $tipoImagem = strtolower(pathinfo($caminhoFinal, PATHINFO_EXTENSION));

    // Verifica se é uma imagem válida
    $verificacao = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($verificacao === false) {
        $mensagem = "O arquivo enviado não é uma imagem.";
    } elseif ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
        $mensagem = "A imagem é muito grande (máx. 2MB).";
    } elseif (!in_array($tipoImagem, ["jpg", "jpeg", "png", "gif", "webp"])) {
        $mensagem = "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
    } elseif (move_uploaded_file($_FILES["foto"]["tmp_name"], $caminhoFinal)) {
        $mensagem = "Upload realizado com sucesso!";
        $caminhoImagem = $caminhoFinal;
//---------------------FIM PROCESSO IMAGEM---------------------------------------------------------------------------------------
$sql = "INSERT INTO pet 
(id_cliente, nome, genero, idade, especie, porte, raca, situacao, foto, statusPet)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'disponivel')";

$stmt = $conexao->prepare($sql);
$stmt->bind_param(
    "issssssss",
    $id_cliente,
    $nome,
    $genero,
    $idade,
    $especie,
    $porte,
    $raca,
    $situacao,
    $caminhoImagem
);
if ($stmt->execute()) {
    $id_pet = $stmt->insert_id;

    $sqlHistorico = "INSERT INTO historico (id_pet, id_cliente, status_pet)
                     VALUES (?, ?, 'disponivel')";
    $stmtH = $conexao->prepare($sqlHistorico);
    $stmtH->bind_param("ii", $id_pet, $id_cliente);
    $stmtH->execute();

    header("Location: ../../Paginas/adote.php");
    exit;
}
}};
?>