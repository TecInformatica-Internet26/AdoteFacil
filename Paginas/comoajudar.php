<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Adote Fácil</title>
    <link rel="icon" href="../IMG/icones/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/padrao.css"> 
    <link rel="stylesheet" href="../CSS/comoajudar.css"> 
    <script src="../JS/padrao.js" defer></script>
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
                    <li class="li-dropdown linkSobre"><a href="sobre.php">Sobre Nós</a></li>
                    <li class="li-dropdown linkAdote"><a href="adote.php">Adote um pet</a></li>
                    <li class="li-dropdown linkCajudar "><a href="comoajudar.php" class="active">Como ajudar</a></li>
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
        <section class="titulo">
            <h1>Como Ajudar</h1>
            <div class="linha-decorativa"></div>
        </section>
        <section id="ajuda">
            <h3>Doando ou divulgando, você salva vidas. Faça parte!</h3>

            <p>
                Todos os dias, animais são abandonados e ignorados. Mas com a sua ajuda, podemos mudar essas histórias. Ao doar, adotar ou divulgar, você oferece uma nova chance para um ser que só precisa de amor.
            </p>

            <h2>Formas de ajudar</h2>

            <ul>
                <li><strong>Adoção responsável:</strong> Dar um lar a um animal muda o mundo dele para sempre.</li>
                <li><strong>Doações financeiras:</strong> Toda quantia ajuda com ração, vacinas, castração e cuidados veterinários.</li>
                <li><strong>Itens físicos:</strong> Ração, produtos de limpeza, cobertores, brinquedos e medicamentos são bem-vindos.</li>
                <li><strong>Voluntariado:</strong> Ajudar presencialmente ou com tarefas online (redes sociais, fotos, vídeos).</li>
                <li><strong>Divulgação:</strong> Compartilhe nossos pets e campanhas. Um clique pode mudar uma vida.</li>
                <li><strong>Eventos solidários:</strong> Participe de feiras de adoção, rifas e bazares beneficentes.</li>
                <li><strong>Parcerias:</strong> Empresas locais podem apoiar com serviços, produtos ou campanhas.</li>
            </ul>

            <p>
                Não importa a forma — toda ajuda importa. Com um gesto seu, uma vida pode ser transformada.
            </p>

            <section id="call-to-action">
                 <h2>Seja a mudança. Salve uma vida hoje.</h2>
            </section>
        </section>
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

</body>
</html>