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
  <link rel="stylesheet" href="../CSS/padrao.css" />
  <link rel="stylesheet" href="../CSS/sobreNos.css" />
  <script src="../JS/sobre.js" defer></script>
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
                    <li class="li-dropdown linkSobre"><a href="sobre.php" class="active">Sobre Nós</a></li>
                    <li class="li-dropdown linkAdote"><a href="adote.php">Adote um pet</a></li>
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

    <div class="container">
        <section class="titulo">
            <h1>Quem Somos</h1>
            <div class="linha-decorativa"></div>
        </section>
        
        <p>Adote Fácil é uma ONG que nasceu do amor pelos animais e do desejo de fazer a diferença na vida de cães e gatos abandonados. Atuamos com responsabilidade, carinho e dedicação para resgatar, cuidar e encaminhar animais para lares cheios de amor.
        </p>
        <H2>Nossa missão</H2>
        <p>Promover a adoção responsável e transformar a realidade de animais em situação de rua ou maus-tratos, garantindo a eles saúde, segurança e uma nova chance de serem felizes.</p>
        <h2>O que fazemos</h2>
        <p>Resgatamos animais abandonados ou em risco, oferecendo acolhimento, cuidados veterinários, alimentação e muito carinho. Após o tratamento, buscamos lares responsáveis através de feiras e campanhas de adoção.

        Também realizamos ações de conscientização sobre guarda responsável e incentivamos a castração como forma de controle populacional.</p>

        <h2>Como você pode ajudar</h2>

        <p>Você pode fazer parte dessa missão de várias formas: adotando um pet, sendo voluntário, fazendo doações ou divulgando nosso trabalho. Toda ajuda conta — e cada gesto salva uma vida!</p>

        <h2>O que doar</h2>

        <ul>
            <li>Dinheiro</li>
            <li>Ração</li>
            <li>Brinquedos</li>
            <li>Outros itens para pets</li>
        </ul>

       <h2> Por que existimos</h2>

        <p>Porque acreditamos que todo animal merece respeito, amor e uma família. Enquanto houver um animal sofrendo nas ruas, estaremos aqui para acolher, cuidar e lutar por ele.</p>
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