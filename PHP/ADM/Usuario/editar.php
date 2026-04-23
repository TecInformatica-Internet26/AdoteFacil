<?php
// mostrar erros para dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('../../conexao.php'); // deve vir antes de qualquer uso de $conexao

// ID pode vir via GET (quando abrimos a tela) ou via POST (quando form enviado)
$idURL = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : null);

if (!$idURL) {
    die("ID não informado.");
}

// --- SE FOR POST: processa atualização ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Recebe valores (sanitiza onde faz sentido)
    $id = $idURL;
    $nome = trim($_POST['nome'] ?? '');
    $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $dataNasc = $_POST['data_nasc'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');
    $whatsapp = preg_replace('/\D/', '', $_POST['whatsapp'] ?? '');
    $estado = $_POST['estado'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $nivel_acesso = $_POST['nivel_acesso'] ?? '';

    $campos_para_atualizar = [];
    $parametros = [];
    $tipos = '';

    function addField(&$campos, &$tipos, &$params, $campo, $tipo, $valor) {
        // aqui só adicionamos se o campo tiver algum valor (evita sobrescrever com vazio)
        if ($valor !== '' && $valor !== null) {
            $campos[] = "$campo = ?";
            $tipos .= $tipo;
            $params[] = $valor;
        }
    }

    addField($campos_para_atualizar, $tipos, $parametros, 'nome', 's', $nome);
    addField($campos_para_atualizar, $tipos, $parametros, 'cpf', 's', $cpf);
    addField($campos_para_atualizar, $tipos, $parametros, 'data_nasc', 's', $dataNasc);
    addField($campos_para_atualizar, $tipos, $parametros, 'sexo', 's', $sexo);
    addField($campos_para_atualizar, $tipos, $parametros, 'email', 's', $email);
    addField($campos_para_atualizar, $tipos, $parametros, 'telefone', 's', $telefone);
    addField($campos_para_atualizar, $tipos, $parametros, 'whatsapp', 's', $whatsapp);
    addField($campos_para_atualizar, $tipos, $parametros, 'estado', 's', $estado);
    addField($campos_para_atualizar, $tipos, $parametros, 'cidade', 's', $cidade);
    addField($campos_para_atualizar, $tipos, $parametros, 'nivel_acesso', 's', $nivel_acesso);

    if (!empty($senha)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        addField($campos_para_atualizar, $tipos, $parametros, 'senha', 's', $senhaHash);
    }

    // processar upload se houver
    if (!empty($_FILES['foto']['name'])) {
        $nomeArquivo = uniqid() . "_" . basename($_FILES['foto']['name']);
        $caminho = "../../../IMG/usuario/" . $nomeArquivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho)) {
            addField($campos_para_atualizar, $tipos, $parametros, 'foto', 's', $nomeArquivo);
        }
    }

    if (empty($campos_para_atualizar)) {
        // não houve campos para atualizar (talvez só clicou sem alterar nada)
        echo "Nenhum campo foi alterado.";
        // se preferir redirecionar de volta em vez de parar, substitua pela header abaixo:
        // header("Location: editar.php?id=".$idURL."&msg=nothing");
        exit;
    }

    $sql = "UPDATE cliente SET " . implode(', ', $campos_para_atualizar) . " WHERE id_cliente = ?";
    $tipos .= 'i';
    $parametros[] = $id;

    $stmt = mysqli_prepare($conexao, $sql);
    if (!$stmt) {
        die("Erro na preparação do statement: " . mysqli_error($conexao));
    }

    // bind com referências
    $refs = [];
    $refs[] = $tipos;
    foreach ($parametros as $k => $v) {
        $refs[] = &$parametros[$k];
    }

    call_user_func_array([$stmt, 'bind_param'], $refs);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: consulta.php?status=success');
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conexao);
        exit;
    }
}

// --- SE NÃO FOR POST: carrega os dados do cliente para preencher o form ---
$query = mysqli_prepare($conexao, "SELECT * FROM cliente WHERE id_cliente = ?");
mysqli_stmt_bind_param($query, "i", $idURL);
mysqli_stmt_execute($query);
$res = mysqli_stmt_get_result($query);
$cliente = mysqli_fetch_assoc($res);

if (!$cliente) {
    die("Cliente não encontrado.");
}

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
?>
<!-- a partir daqui vem o HTML do form (mantive seu layout) -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Usuário</title>
<link rel="stylesheet" href="../../../CSS/consultaedit.css">
<link rel="stylesheet" href="../../../CSS/padrao.css">
<script src="../../../JS/padrao.js" defer></script>
<script src="../../../JS/cadastrar.js" defer></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="../../../index.php"><img src="../../../IMG/LogoTransparente.png" alt="logo_Adote_Fácil"></a>
            </div>
            <div class="dropdown">
                <input type="checkbox" id="burger-menu">
                <label class="burger" for="burger-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                <ul class="dropdown-content">
                    <li class="li-dropdown linkIndex"><a href="../../../index.php">Início</a></li>
                    <li class="li-dropdown linkSobre"><a href="../../../Paginas/sobre.php">Sobre Nós</a></li>
                    <li class="li-dropdown linkAdote"><a href="../../../Paginas/adote.php">Adote um pet</a></li>
                    <li class="li-dropdown linkCajudar"><a href="../../../Paginas/comoajudar.php">Como ajudar</a></li>
                    <?php 
                    if (
                        isset($_SESSION['usuario_email'], $_SESSION['usuario_id']) &&
                        $_SESSION['usuario_email'] === "admadote@gmail.com" &&
                        $_SESSION['usuario_id'] == 1   // <-- coloque o ID correto aqui
                    ): ?>
                        <li class="li-dropdown linkAdmin"><a href="../../../PHP/ADM/Usuario/consulta.php" class="active">Admin</a></li>
                    <?php endif; ?>


                    <?php if (!isset($_SESSION['usuario_id'])): ?>
                        <li class=" li-dropdown "><a href="../../../Paginas/entrar.php" id="btn-entrar" class="botao-entrar">Entrar</a></li>
                    <?php else: ?>
                        <div class="usuario-box" id="userMenu">
                            <?php
                                $foto = $_SESSION['usuario_foto'] ?? '';
                                $nome = $_SESSION['usuario_nome'] ?? 'Usuário';

                                $partes = explode(' ', trim($nome));
                                $iniciais = strtoupper($partes[0][0] . ($partes[1][0] ?? ''));
                                ?>
                                                        
                                <?php if (!empty($foto)): ?>
                                    <img src="../../../IMG/usuario/<?php echo $foto; ?>" class="foto-perfil" alt="Foto">
                                <?php else: ?>
                                    <div class="foto-inicial"><?php echo $iniciais; ?></div>
                            <?php endif; ?>

                            <div class="dropdown-user">
                                <span class="nome-dropdown">
                                    <?php echo explode(" ", $_SESSION['usuario_nome'])[0]; ?>
                                </span>

                                <a href="../../../PHP/Usuario/perfil.php" class="link-perfil">Perfil</a>
                                <a href="../../../PHP/Usuario/logout.php" class="link-perfil">Sair</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">
            <h1>Editar Usuário</h1>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_cliente" value="<?= $idURL ?>">

                <label>Nome</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>

                <label>CPF</label>
                <input type="text" name="cpf" value="<?= formatarCPF($cliente['cpf']) ?>" maxlength="14" required oninput="mascaraCPF(this)">

                <label>Data de Nascimento</label>
                <input type="date" name="data_nasc" value="<?= htmlspecialchars($cliente['data_nasc']) ?>" required>

                <label>E-mail</label>
                <input type="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>

                <label>Telefone</label>
                <input type="text" name="telefone" value="<?= formatarTelefone($cliente['telefone']) ?>" oninput="mascaraTel(this)">

                <label>Whatsapp</label>
                <input type="text" name="whatsapp" value="<?= formatarTelefone($cliente['whatsapp']) ?>" oninput="mascaraTel(this)">

                <label>Estado</label>
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
                            $selected = ($cliente['estado'] == $sigla) ? 'selected' : '';
                            echo "<option value='$sigla' $selected>$nome</option>";
                        }
                    ?>
                </select>

                <label>Cidade</label>
                <select name="cidade" id="cidade" class="input-info" required>
                    <option value="<?= htmlspecialchars($cliente['cidade']) ?>" selected><?= htmlspecialchars($cliente['cidade']) ?></option>
                </select>

                <div class="form-img">
                    <label>Mudar Foto:</label>
                    <?php
                        $foto = $cliente['foto'] ?? '';
                        $nome = $cliente['nome'] ?? 'Usuário';

                        // gerar iniciais
                        $partes = explode(' ', trim($nome));
                        $iniciais = strtoupper($partes[0][0] . ($partes[1][0] ?? ''));
                        ?>

                        <?php if (!empty($foto)): ?>
                            <img src="../../../IMG/usuario/<?= htmlspecialchars($foto) ?>" 
                                alt="Foto do perfil" class="fotoPerfil preview-foto">
                        <?php else: ?>
                            <div class="foto-inicial-perfil"><?= $iniciais ?></div>
                        <?php endif; ?>
                </div>
                <input type="file" name="foto">

                <div class="links">
                    <button type="submit">Salvar Alterações</button>
                    <br><br>
                    <a href="consulta.php" class="btn-voltar">Voltar</a>
                </div>
            </form>
        </div>
    </main>
<footer>
    <section class="footer">
        <div class="footer-coluna" id="cl1">
            <h2>Peludinhos do bem</h2>
            <p>08989-8989898</p>
            <p>Rua Santa Helena, 21, Parque Alvorada,<br> Imperatriz-MA, CEP 65919-505</p>
            <p>adotefacil@peludinhosdobem.org</p>
        </div>

        <div class="footer-coluna" id="cl2">
            <a href="Paginas/sobre.php"><h2>Conheça a História da Peludinhos do Bem</h2></a>
            
        </div>

        <div class="footer-coluna" id="cl3">
            <h2>Contatos</h2>

            <div class="icons-row">
                <a href="https://www.instagram.com/">
                <img src="../../../IMG/index/insta.png" alt="Instagram">
                </a>

                <a href="https://web.whatsapp.com/">
                <img src="../../../IMG/index/—Pngtree—whatsapp icon whatsapp logo whatsapp_3584845.png" alt="Whatsapp">
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
    const cidadeDoBanco = "<?= htmlspecialchars($cliente['cidade']) ?>";
    const estadoDoBanco = "<?= htmlspecialchars($cliente['estado']) ?>";

    document.getElementById('estado').value = estadoDoBanco;
    carregarCidades(cidadeDoBanco);
});
</script>
</body>
</html>
