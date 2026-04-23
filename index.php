<?php
include('PHP/conexao.php');
session_start();

// --- PETS RECENTES ---
$sql = "SELECT * FROM pet WHERE statusPet = 'disponivel' ORDER BY id_pet DESC LIMIT 4";
$result = mysqli_query($conexao, $sql);

if ($result) {
    $pet = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
} else {
    echo "Erro na consulta: " . mysqli_error($conexao);
    $pet = [];
}

// --- PESQUISA PET ---
if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['busca'])) {
    $termo = trim($_GET['busca']);

    $sql = "SELECT * FROM pet 
        WHERE statusPet = 'disponivel'
        AND (
            porte LIKE ? 
            OR raca LIKE ?
            OR nome LIKE ?
            OR especie LIKE ?
        )
        ORDER BY id_pet DESC";

    $stmt = mysqli_prepare($conexao, $sql);

    $like = "%" . $termo . "%";
    mysqli_stmt_bind_param($stmt, "ssss", $like, $like, $like, $like);

    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    $pet = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Adote Fácil</title>
  <link rel="icon" href="IMG/icones/favicon.png" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="CSS/padrao.css">
  <link rel="stylesheet" href="CSS/index.css">
  <script src="JS/padrao.js" defer></script>
  <script src="JS/index.js" defer></script>
  <script src="JS/depoimentos.js" defer></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="index.php"><img src="IMG/LogoTransparente.png" alt="logo_Adote_Fácil"></a>
            </div>
            <div class="dropdown">
                <input type="checkbox" id="burger-menu">
                <label class="burger" for="burger-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                <ul class="dropdown-content">
                    <li class="li-dropdown linkIndex"><a href="index.php" class="active">Início</a></li>
                    <li class="li-dropdown linkSobre"><a href="Paginas/sobre.php">Sobre Nós</a></li>
                    <li class="li-dropdown linkAdote"><a href="Paginas/adote.php">Adote um pet</a></li>
                    <li class="li-dropdown linkCajudar"><a href="Paginas/comoajudar.php">Como ajudar</a></li>
                    <?php 
                    if (
                        isset($_SESSION['usuario_email'], $_SESSION['usuario_id']) &&
                        $_SESSION['usuario_email'] === "admadote@gmail.com" &&
                        $_SESSION['usuario_id'] == 1   // <-- coloque o ID correto aqui
                    ): ?>
                        <li class="li-dropdown linkAdmin"><a href="PHP/ADM/Usuario/consulta.php">Admin</a></li>
                    <?php endif; ?>


                    <?php if (!isset($_SESSION['usuario_id'])): ?>
                        <li class=" li-dropdown "><a href="Paginas/entrar.php" id="btn-entrar" class="botao-entrar">Entrar</a></li>
                    <?php else: ?>
                        <div class="usuario-box" id="userMenu">
                            <?php
                                $foto = $_SESSION['usuario_foto'] ?? '';
                                $nome = $_SESSION['usuario_nome'] ?? 'Usuário';

                                $partes = explode(' ', trim($nome));
                                $iniciais = strtoupper($partes[0][0] . ($partes[1][0] ?? ''));
                                ?>
                                                        
                                <?php if (!empty($foto)): ?>
                                    <img src="IMG/usuario/<?php echo $foto; ?>" class="foto-perfil" alt="Foto">
                                <?php else: ?>
                                    <div class="foto-inicial"><?php echo $iniciais; ?></div>
                            <?php endif; ?>
                            <div class="dropdown-user">
                                <span class="nome-dropdown">
                                    <?php echo explode(" ", $_SESSION['usuario_nome'])[0]; ?>
                                </span>

                                <a href="PHP/Usuario/perfil.php" class="link-perfil">Perfil</a>
                                <a href="PHP/Usuario/logout.php" class="link-perfil">Sair</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    	<section class="carrossel">
            <div class="slides-container">
                <div class="slide ativo">
                <img src="IMG/index/veja-os-melhores-lugares-para-passear-com-pet-em-sao-paulo-tutora-e-o-cao-conexao123.webp" alt="Slide 1">
                <div class="slide-texto">
                    <h2>Bem-vindo ao Adote Fácil</h2>
                    <p>Transforme a vida de um pet com amor e cuidado.</p>
                    <a href="Paginas/adote.php" class="btn-slide">Adotar agora</a>
                </div>
                </div>
                <div class="slide">
                <img src="IMG/index/filhote1.png" alt="Slide 2">
                <div class="slide-texto">
                    <h2>Ajude a nossa causa</h2>
                    <p>Doe, compartilhe ou seja voluntário. Toda ajuda importa!</p>
                    <a href="Paginas/comoajudar.php" class="btn-slide">Como ajudar</a>
                </div>
                </div>
                <div class="slide">
                <img src="IMG/index/enriquecimento-gato3.jpg" alt="Slide 3">
                <div class="slide-texto">
                    <h2>Conheça nossos parceiros</h2>
                    <p>Petshops, clínicas e apoiadores que fazem a diferença.</p>
                    <a href="Paginas/comoajudar.php" class="btn-slide">Ver parcerias</a>
                </div>
                </div>
            </div>
            <p class="seta esquerda"><</p>
            <p class="seta direita">></p>
        </section>
	<main class="main">
		<section class="comunidade">
            <div class="comunidade-content">
                <h1>Junte-se à Nossa Comunidade</h1>
                <p>Todos podem fazer parte dessa transformação. Seja voluntário, apoie com doações ou ajude compartilhando nossos animais. Sua atitude pode mudar uma vida.</p>
                <div class="opcoes-comunidade">
                    <div class="opcao">
                        <h2>Seja Voluntário</h2>
                        <p>Ajude nas feirinhas, lares temporários ou redes sociais.</p>
                        <a href="Paginas/comoajudar.php" class="botao-link">Seja um Voluntário</a>
                    </div>

                    <div class="opcao">
                        <h2>Apoie com Doações</h2>
                        <p>Sua atitude pode mudar uma vida</p>
                        <a href="Paginas/comoajudar.php" class="botao-link">Doe Agora</a>
                    </div>

                    <div class="opcao">
                        <h2>Compartilhe nas Redes</h2>
                        <p>Divulgue um pet e aumente as chances de adoção.</p>
                        <button class="botao-link">Divulgue</button>
                    </div>

                    <div class="opcao">
                        <h2>Parcerias Locais</h2>
                        <p>Tem um petshop ou clínica? Torne-se parceiro da causa.</p>
                        <a class="botao-link" href="https://wa.me/5599991148710?text=Ol%C3%A1,%20gostaria%20de%20saber%20como%20podemos%20fazer%20uma%20parceria%20com%20a%20ONG%20Adote%20F%C3%A1cil.
    ">Fazer parceria</a>
                    </div>
                </div>
            </div>
		</section>
		<section class="cards-vitrini">
            <h1>Conheça pets em busca de uma familía</h1>
			<?php if (count($pet) > 0): ?>
                <div class="vitrine">
                    <?php foreach ($pet as $animal): ?>
                        <div class="pet-card">
                            <div class="pet-imagem">
                                <img src="IMG/adote/<?= htmlspecialchars($animal['foto'])?>" alt="cachorrinho fofo" />
                             </div>
                             <div class="pet-info">
                                <h2><?php echo $animal['nome']; ?></h2>
                                <p><img src="IMG/icones/idadeicon.png" alt="idade_icone" class="iconpet"><?php echo $animal['idade']; ?> anos</p>
                                <?php 
                                    $generoIcon = $animal['genero'] === 'Macho' 
                                        ? 'machoicon.png' 
                                        : 'femeaicon.png';
                                ?>
                                <p>
                                    <img src="IMG/icones/<?= $generoIcon ?>" alt="genero_icone" class="iconpet">
                                    <?php echo $animal['genero']; ?>
                                </p>
                                <p><img src="IMG/icones/porteicon.png" alt="porte_icone" class="iconpet"><?php echo $animal['porte']; ?></p>
                                <p><img src="IMG/icones/situacaoicon.png" alt="situacao_icone" class="iconpet"><?php echo $animal['situacao']; ?></p>
                                <?php 
                                    $especieIcon = $animal['especie'] === 'Cachorro' 
                                        ? 'cachorroicon.png'
                                        : 'gatoicon.png';
                                ?>
                                <p>
                                    <img src="IMG/icones/<?= $especieIcon ?>" alt="especie_icone" class="iconpet">
                                    <?php echo $animal['especie']; ?>
                                </p>

                                <p><img src="IMG/icones/racaicon.png" alt="raca_icone" class="iconpet"><?php echo $animal['raca']; ?></p>
                            </div>
                                <div class="div-qadot">
                                    <button class="qadot" onclick="abrirPopup('https://wa.me/5599991148710?text=Ol%C3%A1%2C%20me%20interessei%20em%20um%20pet%2C%20gostaria%20de%20saber%20mais%20sobre.')">Quero adotar</button>
                                </div>
                            </div>
                    <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum pet cadastrado.</p>
            <?php endif; ?>
                </div>
           <nav class="vejamais">
                <a href="Paginas/adote.php">Veja mais <br><img src="IMG/index/Seta-cinza.png" alt=""></a>
            </nav>
		</section>
	</main>
    <div class="background-depoimentos">
        <div class="container-depoimentos">
        <div class="header-depoimentos">
            <h2>O que dizem sobre os peludinhos do bem?</h2>
        </div>

        <div class="carousel-wrapper">
            <button class="carousel-nav prev" onclick="moveCarousel(-1)">‹</button>
            <button class="carousel-nav next" onclick="moveCarousel(1)">›</button>
            
            <div class="carousel-container" id="carouselContainer">
                
                <div class="testimonial-card">
                    <div class="author-info">
                        <img src="IMG/comoajudar/depoimento1.jpg" class="author-photo">
                        <div class="author-details">
                            <h3>Maria S.</h3>
                            <p>Tutora do Thor</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "Adotar com a Peludinhos do Bem mudou minha vida e a do Thor..."
                    </div>
                    <div class="stars"><span class="star">★★★★★</span></div>
                </div>

                <div class="testimonial-card">
                    <div class="author-info">
                        <img src="IMG/comoajudar/depoimentos.08.png" class="author-photo">
                        <div class="author-details">
                            <h3>João</h3>
                            <p>Tutor do Rex</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "Ter adotado aqui foi uma das melhores decisões..."
                    </div>
                    <div class="stars"><span class="star">★★★★★</span></div>
                </div>

                <div class="testimonial-card">
                    <div class="author-info">
                        <img src="IMG/comoajudar/depoimento3.jpg" class="author-photo">
                        <div class="author-details">
                            <h3>Marcelo M.</h3>
                            <p>Voluntário</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "Doar um pouco do meu tempo me fez sentir parte de algo maior..."
                    </div>
                    <div class="stars"><span class="star">★★★★★</span></div>
                </div>

                <div class="testimonial-card">
                    <div class="author-info">
                        <img src="IMG/comoajudar/depoimentos.07.png" class="author-photo">
                        <div class="author-details">
                            <h3>Carlos R.</h3>
                            <p>Apoiador</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "Conhecer o trabalho da Peludinhos do Bem me inspirou muito..."
                    </div>
                    <div class="stars"><span class="star">★★★★★</span></div>
                </div>

                <div class="testimonial-card">
                    <div class="author-info">
                        <img src="IMG/comoajudar/depoimento2.jpg" class="author-photo">
                        <div class="author-details">
                            <h3>Ana Paula</h3>
                            <p>Tutora da Luna</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "A Luna chegou na minha vida e trouxe tanta alegria!"
                    </div>
                    <div class="stars"><span class="star">★★★★★</span></div>
                </div>

                <div class="testimonial-card">
                    <div class="author-info">
                        <img src="IMG/comoajudar/depoimento4.jpg" class="author-photo">
                        <div class="author-details">
                            <h3>Pedro Santos</h3>
                            <p>Tutor do Bob</p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        "Adotar foi fácil, teve acompanhamento e tudo..."
                    </div>
                    <div class="stars"><span class="star">★★★★★</span></div>
                </div>

            </div>

            <div class="carousel-dots" id="carouselDots"></div>
        </div>
        </div>
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
                <a href="Paginas/sobre.php"><h2>Conheça a História da Peludinhos do Bem</h2></a>
                
            </div>

            <div class="footer-coluna" id="cl3">
                <h2>Contatos</h2>

                <div class="icons-row">
                    <a href="https://www.instagram.com/">
                    <img src="IMG/index/insta.png" alt="Instagram">
                    </a>

                    <a href="https://web.whatsapp.com/">
                    <img src="IMG/index/—Pngtree—whatsapp icon whatsapp logo whatsapp_3584845.png" alt="Whatsapp">
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
