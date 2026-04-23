<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require "../conexao.php";

// Se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Sessão expirada. Faça login novamente.'); window.location.href='entrar.php';</script>";
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// ---------------------- //
// PROCESSAR O FORMULÁRIO //
// ---------------------- //
// PROCESSAR O FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitização e Coalescência (Garante que a variável existe)
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $dataNasc = $_POST['dataNascimento'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $whatsapp = $_POST['whats'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    
    $nova_senha = trim($_POST['nova_senha'] ?? ''); 
    $senha_atual_digitada = trim($_POST['senha_atual'] ?? '');

    // Inicialização para montagem dinâmica do SQL
    $campos_para_atualizar = [
    "nome = ?",
    "data_nasc = ?",
    "email = ?", 
    "telefone = ?", 
    "whatsapp = ?", 
    "estado = ?", 
    "cidade = ?"
    ];

    $tipos = "sssssss";
    $parametros = [
        $nome, $dataNasc, $email, $telefone, $whatsapp, $estado, $cidade
    ];

    // 1. Lógica da Senha 
    if (!empty($nova_senha)) {
        // 1.1. Verifica se a senha atual foi fornecida
        if (empty($senha_atual_digitada)) {
            echo "<script>alert('Para alterar a senha, você deve fornecer a Senha Atual.'); window.location.href='editar.php';</script>";
            exit;
        }

        // 1.2. Busca o hash da senha atual no banco
        $sql_senha = "SELECT senha FROM cliente WHERE id_cliente = ?";
        $stmt_senha = $conexao->prepare($sql_senha);
        $stmt_senha->bind_param("i", $id_usuario);
        $stmt_senha->execute();
        $res_senha = $stmt_senha->get_result();
        $dados_senha = $res_senha->fetch_assoc();
        $hash_senha_bd = $dados_senha['senha'];

        // 1.3. Verifica a senha
        if (!password_verify($senha_atual_digitada, $hash_senha_bd)) {
            echo "<script>alert('A Senha Atual fornecida está incorreta.'); window.location.href='editar.php';</script>";
            exit;
        }
        
        // Se a senha atual está correta, atualiza com a nova senha
        $senhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $campos_para_atualizar[] = "senha = ?";
        $tipos .= "s";
        $parametros[] = $senhaHash;
    }

    // 2. Lógica da Foto (Opcional)
    $novoNomeFoto = null;
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $novoNomeFoto = uniqid() . "." . $ext;
        
        // Move o arquivo para o destino
        move_uploaded_file($_FILES['foto']['tmp_name'], "../../IMG/usuario/" . $novoNomeFoto);

        $campos_para_atualizar[] = "foto = ?";
        $tipos .= "s";
        $parametros[] = $novoNomeFoto;
    }

    // Monta a query final
    $sql = "UPDATE cliente SET " . implode(", ", $campos_para_atualizar) . " WHERE id_cliente = ?";
    
    // Adiciona o tipo e o parâmetro do ID do usuário no final
    $tipos .= "i";
    $parametros[] = $id_usuario;

    // Prepara e executa o statement
    $stmt = $conexao->prepare($sql);
    
    if (!$stmt) {
        // Se houver um erro de sintaxe no SQL, ele será exibido aqui.
        die("Erro na preparação do SQL: " . $conexao->error);
    }

    // Chama o bind_param dinamicamente (necessário quando o número de parâmetros muda)
    // O array_merge junta a string de tipos com o array de valores
    call_user_func_array([$stmt, 'bind_param'], array_merge([$tipos], $parametros));

    $stmt->execute();

    // Redirecionamento de Sucesso
    // Se o usuário alterou a foto, precisamos atualizar a sessão
    if (!empty($novoNomeFoto)) {
        $_SESSION['usuario_foto'] = $novoNomeFoto;
    }

    echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href='perfil.php';</script>";
    exit;

    function formatarCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    if (strlen($cpf) === 11) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }
    return $cpf;
}
function formatarTelefone($tel) {
    $tel = preg_replace('/\D/', '', $tel); // remove tudo que não é número
    
    if (strlen($tel) === 10) {
        // Formato: (99) 9999-9999
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $tel);
    } 
    
    if (strlen($tel) === 11) {
        // Formato: (99) 9 9999-9999
        return preg_replace('/(\d{2})(\d{1})(\d{4})(\d{4})/', '($1) $2 $3-$4', $tel);
    }

    return $tel; // retorna como está se for diferente
}
}

// --------------------------- //
// BUSCA OS DADOS DO USUÁRIO   //
// --------------------------- //
$sql = "SELECT * FROM cliente WHERE id_cliente = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$usuario = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="icon" href="../../IMG/icones/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../CSS/padrao.css">
    <link rel="stylesheet" href="../../CSS/perfil.css">
    <script src="../../JS/padrao.js" defer></script>
    <script src="../../JS/cadastrar.js" defer></script>
</head>
<body>
<header>
        <nav class="navbar">
            <div class="logo">
                <a href="../../index.php"><img src="../../IMG/LogoTransparente.png" alt="logo_Adote_Fácil"></a>
            </div>
            <div class="dropdown">
                <input type="checkbox" id="burger-menu">
                <label class="burger" for="burger-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                <ul class="dropdown-content">
                    <li class="li-dropdown linkIndex"><a href="../../index.php">Início</a></li>
                    <li class="li-dropdown linkSobre"><a href="../../Paginas/sobre.php">Sobre Nós</a></li>
                    <li class="li-dropdown linkAdote"><a href="../../Paginas/adote.php">Adote um pet</a></li>
                    <li class="li-dropdown linkCajudar"><a href="../../Paginas/comoajudar.php">Como ajudar</a></li>
                    <?php 
                    if (
                        isset($_SESSION['usuario_email'], $_SESSION['usuario_id']) &&
                        $_SESSION['usuario_email'] === "admadote@gmail.com" &&
                        $_SESSION['usuario_id'] == 1   // <-- coloque o ID correto aqui
                    ): ?>
                        <li class="li-dropdown linkAdmin"><a href="../ADM/Usuario/consulta.php">Admin</a></li>
                    <?php endif; ?>


                    <?php if (!isset($_SESSION['usuario_id'])): ?>
                        <li class=" li-dropdown "><a href="../../Paginas/entrar.php" id="btn-entrar" class="botao-entrar">Entrar</a></li>
                    <?php else: ?>
                        <div class="usuario-box" id="userMenu">
                            <?php
                                $foto = $_SESSION['usuario_foto'] ?? '';
                                $nome = $_SESSION['usuario_nome'] ?? 'Usuário';

                                $partes = explode(' ', trim($nome));
                                $iniciais = strtoupper($partes[0][0] . ($partes[1][0] ?? ''));
                                ?>
                                                        
                                <?php if (!empty($foto)): ?>
                                    <img src="../../IMG/usuario/<?php echo $foto; ?>" class="foto-perfil" alt="Foto">
                                <?php else: ?>
                                    <div class="foto-inicial"><?php echo $iniciais; ?></div>
                            <?php endif; ?>

                            <div class="dropdown-user">
                                <span class="nome-dropdown">
                                    <?php echo explode(" ", $_SESSION['usuario_nome'])[0]; ?>
                                </span>

                                <a href="perfil.php" class="link-perfil">Perfil</a>
                                <a href="logout.php" class="link-perfil">Sair</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
</header>
<div class="container">
    <h1>Editar Perfil</h1>

    <?php
                $foto = $usuario['foto'] ?? '';
                $nome = $usuario['nome'] ?? 'Usuário';

                // gerar iniciais
                $partes = explode(' ', trim($nome));
                $iniciais = strtoupper($partes[0][0] . ($partes[1][0] ?? ''));
                ?>

                <?php if (!empty($foto)): ?>
                    <img src="../../IMG/usuario/<?= htmlspecialchars($foto) ?>" 
                        alt="Foto do perfil" class="fotoPerfil">
                <?php else: ?>
                    <div class="foto-inicial-perfil"><?= $iniciais ?></div>
                <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="formEditar">

        <div class="info">
            <label>Nome Completo:</label>
            <input class="input-info" type="text" name="nome" 
                   value="<?= htmlspecialchars($usuario['nome']) ?>" >
        </div>
        
        <div class="info">
            <label>Data de Nascimento:</label>
            <input class="input-info" type="date" name="dataNascimento" 
                   value="<?= htmlspecialchars($usuario['data_nasc']) ?>">
        </div>
        
        <div class="info">
            <label>E-mail:</label>
            <input class="input-info" type="email" name="email" 
                   value="<?= htmlspecialchars($usuario['email']) ?>">
        </div>

        <div class="numeros">
            <div class="info tel">
                <label>Telefone:</label> <br>
                <input class="input-info" type="tel" name="telefone" 
                    value="<?= htmlspecialchars($usuario['telefone']) ?>" maxlength="11">
            </div>

            <div class="info zap">
                <label>WhatsApp:</label> <br>
                <input class="input-info" type="tel" name="whats" 
                    value="<?= htmlspecialchars($usuario['whatsapp']) ?>" maxlength="11">
            </div>
        </div>

        <div class="locais">
            <div class="info estado">
                <label>Estado:</label> <br>
                <select name="estado" id="estado" class="input-info" onchange="carregarCidades()">
                    <option value="">Selecione</option>
                    <?php
                        $estados = [
                            'AC'=>'Acre', 'AL'=>'Alagoas', 'AP'=>'Amapá', 'AM'=>'Amazonas', 'BA'=>'Bahia', 
                            'CE'=>'Ceará', 'DF'=>'Distrito Federal', 'ES'=>'Espírito Santo', 'GO'=>'Goiás', 
                            'MA'=>'Maranhão', 'MT'=>'Mato Grosso', 'MS'=>'Mato Grosso do Sul', 'MG'=>'Minas Gerais', 
                            'PA'=>'Pará', 'PB'=>'Paraíba', 'PR'=>'Paraná', 'PE'=>'Pernambuco', 'PI'=>'Piauí', 
                            'RJ'=>'Rio de Janeiro', 'RN'=>'Rio Grande do Norte', 'RS'=>'Rio Grande do Sul', 
                            'RO'=>'Rondônia', 'RR'=>'Roraima', 'SC'=>'Santa Catarina', 'SP'=>'São Paulo', 
                            'SE'=>'Sergipe', 'TO'=>'Tocantins'
                        ];
                        
                        foreach ($estados as $sigla => $nome) {
                            // Verifica se é o estado do usuário para marcar como selecionado
                            $selected = ($usuario['estado'] == $sigla) ? 'selected' : '';
                            echo "<option value='$sigla' $selected>$nome</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="info cidade">
               <label>Cidade:</label><br>
                <select name="cidade" id="cidade" class="input-info" required>
                    <option value="<?= htmlspecialchars($usuario['cidade']) ?>" selected>
                        <?= htmlspecialchars($usuario['cidade']) ?>
                    </option>
                </select>
            </div>
        </div>

        <div class="info">
            <label>Senha Atual:</label>
            <input class="input-info" type="password" name="senha_atual" 
                   placeholder="Digite sua senha atual para alterar"
                   id="senha_atual_input">
        </div>

        <div class="info">
            <label>Nova Senha:</label>
            <input class="input-info" type="password" name="nova_senha" 
                   placeholder="Preencha a Senha Atual para mudar"
                   id="nova_senha_input" disabled>
        </div>

        <div class="info">
            <label>Nova Foto (opcional):</label>
            <input class="input-info" type="file" name="foto">
        </div>

        <div id="registrar">
            <a href="perfil.php" class="btn btn-primary">⭠ Voltar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>

    <footer>
        <section class="footer">
            <div class="footer-coluna" id="cl1">
                <h2>Peludinhos do bem</h2>
                <p>08989-8989898</p>
                <p>Rua Santa Helena, 21, Parque Alvorada,<br> Imperatriz-MA, CEP 65919-505</p>
                <p>adotefacil@peludinhosdobem.org</p>
            </div>

            <div class="footer-coluna" id="cl2">
                <a href="../../Paginas/sobre.php"><h2>Conheça a História da Peludinhos do Bem</h2></a>
            </div>

            <div class="footer-coluna" id="cl3">
                <h2>Contatos</h2>

                <div class="icons-row">
                    <a href="https://www.instagram.com/">
                    <img src="../../IMG/index/insta.png" alt="Instagram">
                    </a>

                    <a href="https://web.whatsapp.com/">
                    <img src="../../IMG/index/—Pngtree—whatsapp icon whatsapp logo whatsapp_3584845.png" alt="Whatsapp">
                    </a>
                </div>
            </div>
        </section>

        <div class="footer-rodape">
            <p>Desenvolvido pela Turma - 20.8.2025 Tecnico de Informatica para Internet (Peludinhos do Bem). 2025 &copy;Todos os direitos reservados.</p>
        </div>
    </footer>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const cidadeDoBanco = "<?= htmlspecialchars($usuario['cidade']) ?>";
    const estadoDoBanco = "<?= htmlspecialchars($usuario['estado']) ?>";

    document.getElementById('estado').value = estadoDoBanco;

    carregarCidades(cidadeDoBanco);
});

    // Máscara CPF
    const cpf = document.getElementById('cpf');
    if (cpf) {
        IMask(cpf, { mask: '000.000.000-00' });
    }

    // Máscara Telefone
    const telefone = document.getElementById('telefone');
    if (telefone) {
        IMask(telefone, {
            mask: '(00) 00000-0000'
        });
    }

    // Máscara CEP
    const cep = document.getElementById('cep');
    if (cep) {
        IMask(cep, { mask: '00000-000' });
    }
</script>

</body>
</html>