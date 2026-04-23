<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../Paginas/entrar.php');
    exit;
}

$token = $_POST['token'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

// valida token novamente
$sql = "SELECT id_cliente FROM cliente WHERE token_redefinir = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Token inválido.");
}

$user = $res->fetch_assoc();
$id = $user['id_cliente'];

// atualiza senha
$sqlUp = "UPDATE cliente SET senha=?, token_redefinir=NULL, token_expira=NULL WHERE id_cliente=?";
$stmtUp = $conexao->prepare($sqlUp);
$stmtUp->bind_param("si", $senha, $id);
$stmtUp->execute();

echo "<script>
    alert('Senha alterada com sucesso!');
    window.location.href='../../Paginas/entrar.php';
</script>";
exit;

?>