<?php
session_start();
require '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../Paginas/entrar.php');
    exit;
}

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT id_cliente, nome, email, senha, foto FROM cliente WHERE email = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $_SESSION['erro_login'] = "E-mail não encontrado.";
    header('Location: ../../Paginas/entrar.php');
    exit;
}

$user = $res->fetch_assoc();

if (!password_verify($senha, $user['senha'])) {
    $_SESSION['erro_login'] = "Senha incorreta.";
    header('Location: ../../Paginas/entrar.php');
    exit;
}

// LOGIN OK
$_SESSION['usuario_id']    = $user['id_cliente'];
$_SESSION['usuario_nome']  = $user['nome'];
$_SESSION['usuario_email'] = $user['email'];
$_SESSION['usuario_foto']  = $user['foto'];

header('Location: ../../index.php');
exit;
