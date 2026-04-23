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
  <link rel="stylesheet" href="../CSS/entrar.css" />
  <script src="../JS/entrar.js" defer></script>
  <style>
    main{
        background-image: url(../IMG/index/filhotes-de-golden-retriever-fslbs3tert4zvcbd.jpg);
        background-size: cover;
        background-position: center;
    }
    .login-container{
        margin: 200px auto;
    }

  </style>
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
                        <li class=" li-dropdown "><a href="entrar.php" id="btn-entrar" class="botao-entrar active">Entrar</a></li>
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
        <div class="login-container">
            <h1>Recuperar Senha</h1>
            <div class="linha-decorativa"></div>

            <form class="login-form" method="POST" id="formRecuperar">
                <label for="email">Digite seu e-mail cadastrado:</label>
                <input type="email" id="email" name="email" required placeholder="Seu e-mail">
                <button class="botao" type="submit">Enviar link de recuperação</button>
            </form>

            <div class="cadastro-link">
                <a href="entrar.php">Voltar</a>
            </div>
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

    <div id="popup" style="display:none;
position:fixed;top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.5);justify-content:center;align-items:center;">
    <div style="background:#fff;padding:20px;border-radius:10px;text-align:center;">
        <p id="popupMsg"></p>
        <button onclick="fecharPopup()">Ok</button>
    </div>
</div>

<script>
function abrirPopup(msg){
    document.getElementById("popupMsg").innerText = msg;
    document.getElementById("popup").style.display = "flex";
}

function fecharPopup(){
    document.getElementById("popup").style.display = "none";
}

const form = document.getElementById("formRecuperar");

form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const dados = new FormData(form);

    const req = await fetch("../PHP/Usuario/enviarEmailSenha.php", {
        method: "POST",
        body: dados
    });

    const resp = await req.text();

    if (resp === "ok") abrirPopup("Um link foi enviado ao seu email 📩");
    else if (resp === "notfound") abrirPopup("Esse e-mail não está cadastrado ❗");
    else abrirPopup("Erro ao enviar o e-mail 😕");
});
</script>

</body>
</html>
