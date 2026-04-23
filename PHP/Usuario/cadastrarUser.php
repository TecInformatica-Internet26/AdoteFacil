<?php
session_start();
require '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../Paginas/cadastro.php');
    exit;
}

$nome     = $_POST['nome'];
$cpf      = preg_replace('/\D/', '', $_POST['cpf']);
$dataNasc = $_POST['dataNascimento'];
$email    = $_POST['email'];
$telefone = preg_replace('/\D/', '', $_POST['telefone']);
$whatsapp = preg_replace('/\D/', '', $_POST['whats']);
$estado   = $_POST['estado'];
$cidade   = $_POST['cidade'];
$senhaRaw = $_POST['senha'] ?? '';
    
// validações mínimas
if(strlen($cpf) != 11){
    echo "CPF inválido";
    exit;
}
if(strlen($telefone) != 11){
    echo "Telefone inválido";
    exit;
}
if(strlen($whatsapp) != 11){
    echo "WhatsApp inválido";
    exit;
}

// validação básica
if (empty($nome) || empty($email) || empty($senhaRaw)) {
    echo "Preencha nome, email e senha.";
    exit;
}

// hash da senha
$senhaHash = password_hash($senhaRaw, PASSWORD_DEFAULT);

// upload da foto (opcional)
$fotoNome = null;
if (!empty($_FILES['foto']['name'])) {
    $pasta = '../../IMG/usuario/';
    if (!is_dir($pasta)) mkdir($pasta, 0777, true);

    // sanitize do nome + uniqid
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $fotoNome = uniqid('usr_') . '.' . $ext;
    $tmp = $_FILES['foto']['tmp_name'];
    move_uploaded_file($tmp, $pasta . $fotoNome);
}

// checar email duplicado
$sqlCheck = "SELECT id_cliente FROM cliente WHERE email = ?";
$stmt = $conexao->prepare($sqlCheck);
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    echo "Email já cadastrado.";
    exit;
}
$stmt->close();

// inserir usuário
$sql = "INSERT INTO cliente (nome, cpf, data_nasc, email, telefone, whatsapp, estado, cidade, senha, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('ssssssssss', $nome, $cpf, $dataNasc, $email, $telefone, $whatsapp, $estado, $cidade, $senhaHash, $fotoNome);

if ($stmt->execute()) {
    // use $conexao->insert_id para pegar id
    $idInserido = $conexao->insert_id;

    // criar sessão COM AS MESMAS CHAVES QUE A SUA NAV CHECA
    $_SESSION['id_cliente'] = $idInserido;
    $_SESSION['usuario_id']   = $idInserido;
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_email']= $email;
    $_SESSION['usuario_foto'] = $fotoNome; // importante

    // redireciona para uma página PHP
    header('Location: ../../index.php');
    exit;
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}
$stmt->close();
$conexao->close();
?>
