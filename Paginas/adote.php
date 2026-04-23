<?php
include('../PHP/conexao.php');
session_start();

$sql = "SELECT * FROM pet WHERE statusPet = 'disponivel' ORDER BY id_pet DESC";
$result = mysqli_query($conexao, $sql);

if ($result) {
    $pet = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result); // Libera a memória do resultado
} else {
    echo "Erro na consulta: " . mysqli_error($conexao);
    $pet = array(); // Array vazio em caso de erro
}

//PESQUISA PET
$resultados = [];
$termo = '';

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['busca'])) {
    $termo = trim($_GET['busca']);

    $sql = "SELECT * FROM pet 
        WHERE statusPet = 'disponivel' 
        AND (porte LIKE ? 
        OR raca LIKE ?
        OR nome LIKE ?
        OR especie LIKE ?)";

    $stmt = mysqli_prepare($conexao, $sql);
    $like = "%" . $termo . "%";
    mysqli_stmt_bind_param($stmt, "ssss", $like, $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $resultados = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    $pet = $resultados;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Adote Fácil</title>
  <link rel="icon" href="../IMG/icones/favicon.png" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../CSS/padrao.css" />
  <link rel="stylesheet" href="../CSS/adote.css" />
  <script src="../JS/padrao.js" defer></script>
  <script src="../JS/adote.js" defer></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="../index.php"><img src="../IMG/LogoTransparente.png" alt="logo_Adote_Fácil"></a>
            </div>
            <div class="dropdown">
                <input type="checkbox" id="burger-menu">
                <label class="burger" for="burger-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                <ul class="dropdown-content">
                    <li class="li-dropdown linkIndex"><a href="../index.php">Início</a></li>
                    <li class="li-dropdown linkSobre"><a href="sobre.php" >Sobre Nós</a></li>
                    <li class="li-dropdown linkAdote"><a href="adote.php" class="active">Adote um pet</a></li>
                    <li class="li-dropdown linkCajudar"><a href="comoajudar.php">Como ajudar</a></li>
                    <?php 
                    if (
                        isset($_SESSION['usuario_email'], $_SESSION['usuario_id']) &&
                        $_SESSION['usuario_email'] === "admadote@gmail.com" &&
                        $_SESSION['usuario_id'] == 1   // <-- coloque o ID correto aqui
                    ): ?>
                        <li class="li-dropdown linkAdmin"><a href="../PHP/ADM/Usuario/consulta.php">Admin</a></li>
                    <?php endif; ?>


                    <?php if (!isset($_SESSION['usuario_id'])): ?>
                        <li class=" li-dropdown "><a href="entrar.php" id="btn-entrar" class="botao-entrar">Entrar</a></li>
                    <?php else: ?>
                        <div class="usuario-box" id="userMenu">
                            <?php
                                $foto = $_SESSION['usuario_foto'] ?? '';
                                $nome = $_SESSION['usuario_nome'] ?? 'Usuário';

                                $partes = explode(' ', trim($nome));
                                $iniciais = strtoupper($partes[0][0] . ($partes[1][0] ?? ''));
                                ?>
                                                        
                                <?php if (!empty($foto)): ?>
                                    <img src="../IMG/usuario/<?php echo $foto; ?>" class="foto-perfil" alt="Foto">
                                <?php else: ?>
                                    <div class="foto-inicial"><?php echo $iniciais; ?></div>
                            <?php endif; ?>

                            <div class="dropdown-user">
                                <span class="nome-dropdown">
                                    <?php echo explode(" ", $_SESSION['usuario_nome'])[0]; ?>
                                </span>

                                <a href="../PHP/Usuario/perfil.php">Perfil</a>
                                <a href="../PHP/Usuario/logout.php">Sair</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="intro-adote">
            <section class="titulo">
                <h1>Encontre seu novo melhor amigo</h1>
                <div class="linha-decorativa"></div>
            </section>
            <p>Adotar é um gesto de amor. Veja os pets disponíveis e transforme uma vida.</p>
            <form class="search-box" method="GET">
                <input type="text" name="busca" placeholder="Buscar por nome, raça, porte..."
                    value="<?php echo htmlspecialchars($termo ?? ''); ?>">
                <button type="submit">Pesquisar</button>
            </form> 
        </section>
    <?php if (!empty($pet)): ?>
                <div class="vitrine">
                    <?php foreach ($pet as $animal): ?>
                        <div class="pet-card">
                            <div class="pet-imagem">
                                <img src="../IMG/adote/<?= htmlspecialchars($animal['foto'])?>" alt="cachorrinho fofo" />
                             </div>
                             <div class="pet-info">
                                    <h2><?php echo $animal['nome']; ?></h2>
                                    <p><img src="../IMG/icones/idadeicon.png" alt="idade_icone" class="iconpet"><?php echo $animal['idade']; ?> anos</p>
                                    <?php 
                                        $generoIcon = $animal['genero'] === 'Macho' 
                                            ? 'machoicon.png' 
                                            : 'femeaicon.png';
                                    ?>
                                    <p>
                                        <img src="../IMG/icones/<?= $generoIcon ?>" alt="genero_icone" class="iconpet">
                                        <?php echo $animal['genero']; ?>
                                    </p>
                                    <p><img src="../IMG/icones/porteicon.png" alt="porte_icone" class="iconpet"><?php echo $animal['porte']; ?></p>
                                        <p><img src="../IMG/icones/situacaoicon.png" alt="situacao_icone" class="iconpet"><?php echo $animal['situacao']; ?></p>
                                    <?php 
                                        $especieIcon = $animal['especie'] === 'Cachorro' 
                                            ? 'cachorroicon.png'
                                            : 'gatoicon.png';
                                    ?>
                                    <p>
                                        <img src="../IMG/icones/<?= $especieIcon ?>" alt="especie_icone" class="iconpet">
                                        <?php echo $animal['especie']; ?>
                                    </p>

                                    <p><img src="../IMG/icones/racaicon.png" alt="raca_icone" class="iconpet"><?php echo $animal['raca']; ?></p>

                            </div>
                                <div class="div-qadot">
                                    <button class="qadot" onclick="abrirPopup('https://wa.me/5599991148710?text=Ol%C3%A1%2C%20me%20interessei%20em%20um%20pet%2C%20gostaria%20de%20saber%20mais%20sobre.')">Quero adotar</button>
                                </div>
                            </div>
                    <?php endforeach; ?>
                </div>
    <?php else: ?>
        <div class="nenhum-resultado">
            <p>❌ Nenhum pet encontrado "<strong><?= htmlspecialchars($termo) ?></strong>".</p>
        </div>

    <?php endif; ?>
        </div>
            <div class="cadastro-pet-container">
                <a href="cadastropet.php" class="cadastro-pet-btn">Quero cadastrar meu pet</a>
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
                <a href="sobre.php"><h2>Conheça a História da Peludinhos do Bem</h2></a>
                
            </div>

            <div class="footer-coluna" id="cl3">
                <h2>Contatos</h2>

                <div class="icons-row">
                    <a href="https://www.instagram.com/">
                    <img src="../IMG/index/insta.png" alt="Instagram">
                    </a>

                    <a href="https://web.whatsapp.com/">
                    <img src="../IMG/index/—Pngtree—whatsapp icon whatsapp logo whatsapp_3584845.png" alt="Whatsapp">
                    </a>
                </div>
            </div>
        </section>

        <div class="footer-rodape">
            <p>Desenvolvido pela Turma - 20.8.2025 Tecnico de Informatica para Internet (Peludinhos do Bem). 2025 &copy;Todos os direitos reservados.</p>
        </div>
    </footer>
    <div id="popup-confirmacao" class="popup-overlay">
        <div class="popup">
            <h2>Confirmar Adoção</h2>
            <p>Você realmente deseja adotar este pet?</p>

            <div class="botoes">
                <button class="btn-cancelar" onclick="fecharPopup()">Cancelar</button>
                <button class="btn-confirmar" id="confirmarBtn">Confirmar</button>
            </div>
        </div>
    </div>

</body>
<script>
let linkDestino = "";

function abrirPopup(link) {
    linkDestino = link;
    document.getElementById("popup-confirmacao").style.display = "flex";
}

function fecharPopup() {
    document.getElementById("popup-confirmacao").style.display = "none";
}

document.getElementById("confirmarBtn").addEventListener("click", function () {
    window.location.href = linkDestino;
});
</script>
</html>