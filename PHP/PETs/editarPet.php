<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('../conexao.php');
if (!isset($_GET['id'])) {
    die("ID do pet não informado.");
}

$id = intval($_GET['id']);
// Buscar dados do pet
$sql = "SELECT * FROM pet WHERE id_pet = $id LIMIT 1";
$result = mysqli_query($conexao, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Pet não encontrado.");
}

$pet = mysqli_fetch_assoc($result);

// Atualizar dados ao enviar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $genero = $_POST['genero'];
    $idade = $_POST['idade'];
    $especie = $_POST['especie'];
    $porte = $_POST['porte'];
    $raca = $_POST['raca'];
    $situacao = $_POST['situacao'];


    // Verifica se enviou nova foto
    if (!empty($_FILES['foto']['name'])) {

        $imagem = $_FILES['foto']['name'];
        $destino = "../../IMG/adote/" . $imagem;

        move_uploaded_file($_FILES['foto']['tmp_name'], $destino);

        // Atualizar incluindo foto
        $sqlUpdate = "UPDATE pet SET 
                        nome='$nome',
                        genero='$genero',
                        idade='$idade',
                        especie='$especie',
                        porte='$porte',
                        raca='$raca',
                        situacao='$situacao',
                        foto='$imagem'
                    WHERE id_pet=$id";
    } else {

        // Atualizar sem mexer na foto
            $sqlUpdate = "UPDATE pet SET 
                nome='$nome',
                genero='$genero',
                idade='$idade',
                especie='$especie',
                porte='$porte',
                raca='$raca',
                situacao='$situacao'
              WHERE id_pet=$id";

    }

    if (mysqli_query($conexao, $sqlUpdate)) {
        header("Location: ../Usuario/perfil.php?id=$id&msg=editado");
        exit();
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conexao);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Pet</title>
<link rel="stylesheet" href="../../CSS/padrao.css">
<link rel="stylesheet" href="../../CSS/consultaedit.css">

<script src="../../JS/padrao.js" defer></script>
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
                    <li class="li-dropdown linkIndex"><a href="../../index.php" class="active">Início</a></li>
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
                            <img src="../../IMG/usuario/<?php echo $_SESSION['usuario_foto']; ?>" 
                                class="foto-perfil" alt="Foto">

                            <div class="dropdown-user">
                                <span class="nome-dropdown">
                                    <?php echo explode(" ", $_SESSION['usuario_nome'])[0]; ?>
                                </span>

                                <a href="../Usuario/perfil.php" class="link-perfil">Perfil</a>
                                <a href="../Usuario/logout.php" class="link-perfil">Sair</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
</header>
<div class="container">
    <h1>Editar Pet</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        
        <label>Nome do Pet</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($pet['nome']) ?>" required>

        <label>Gênero</label>
        <select name="genero" required>
            <option value="">Selecione o gênero</option>
            <option value="Macho" <?= $pet['genero'] == 'Macho' ? 'selected' : '' ?>>Macho</option>
            <option value="Fêmea" <?= $pet['genero'] == 'Fêmea' ? 'selected' : '' ?>>Fêmea</option>
        </select>

        <label>Idade</label>
        <input type="text" name="idade" value="<?= htmlspecialchars($pet['idade']) ?>"required>

        <label>Espécie</label>
        <select name="especie" required>
            <option value="">Selecione a espécie</option>
            <option value="Cachorro" <?= $pet['especie'] == 'Cachorro' ? 'selected' : '' ?>>Cachorro</option>
            <option value="Gato" <?= $pet['especie'] == 'Gato' ? 'selected' : '' ?>>Gato</option>
        </select>


        <label>Porte</label>
        <select name="porte" required>
            <option value="">Selecione o porte</option>
            <option value="Pequeno"  <?= $pet['porte'] == 'Pequeno' ? 'selected' : '' ?>>Pequeno</option>
            <option value="Médio"    <?= $pet['porte'] == 'Médio' ? 'selected' : '' ?>>Médio</option>
            <option value="Grande"   <?= $pet['porte'] == 'Grande' ? 'selected' : '' ?>>Grande</option>
        </select>


        <label>Raça</label>
        <input type="text" name="raca" value="<?= htmlspecialchars($pet['raca']) ?>"required>

        <label>Situação</label>
        <select name="situacao" required>
            <option value="Nenhum"                <?= $pet['situacao'] == 'Nenhum' ? 'selected' : '' ?>>Nenhum</option>
            <option value="Vacinado"              <?= $pet['situacao'] == 'Vacinado' ? 'selected' : '' ?>>Vacinado</option>
            <option value="Castrado"              <?= $pet['situacao'] == 'Castrado' ? 'selected' : '' ?>>Castrado</option>
            <option value="Vacinado e Castrado"   <?= $pet['situacao'] == 'Vacinado e Castrado' ? 'selected' : '' ?>>Vacinado e Castrado</option>
        </select>


        <div class="form-img">                
            <label>Foto Atual:</label><br>
            <img src="../../IMG/adote/<?= htmlspecialchars($pet['foto']) ?>" width="150"><br><br>
        </div>
        
        <label>Mudar Foto:</label>
        <input type="file" name="foto">

        <div class="links">
            <button type="submit">Salvar Alterações</button>
            <br><br>
            <a href="../Usuario/perfil.php" class="btn-voltar">Voltar</a>
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
</body>
</html>
