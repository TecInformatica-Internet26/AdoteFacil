<?php
session_start();
include ('../PHP/conexao.php');

$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT * FROM cliente WHERE id_cliente = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('location: entrar.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Adote Fácil - Cadastro de Pet</title>
  <link rel="icon" href="../IMG/icones/favicon.png" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../CSS/padrao.css">
  <link rel="stylesheet" href="../CSS/cadastropet.css">
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
    <div class="boxpet">
      <form action="../PHP/PETs/cadastrarPET.php" method="post" enctype="multipart/form-data">
        <fieldset>
          <legend><b>Cadastre seu Pet</b></legend>

          <div class="inputBox iboxNome">
            <label for="nome" class="labelinput labelNome">Nome do Animal</label>
            <input type="text" name="nome" id="nome" class="inputUser" maxlength="15" required />
          </div>

          <div class="form-content">
          <div id="duo">
            <div class="inputBox">
              <label for="genero" class="labelinput">Gênero</label>
              <select name="genero" id="genero" required>
                <option value="">Selecione o gênero</option>
                <option value="Macho">Macho</option>
                <option value="Fêmea">Fêmea</option>
              </select>
            </div>
            <div class="inputBox">
              <label for="idade" class="labelinput"><b>Idade</b></label>
              <input type="number" name="idade" id="idade" class="inputUser" min="0"/ required>
            </div>
          </div>
          
            <div id="duo">
          <div class="inputBox">
            <label for="especie" class="labelinput">Espécie</label>
            <select name="especie" id="especie" required>
              <option value="">Selecione a espécie</option>
              <option value="Cachorro">Cachorro</option>
              <option value="Gato">Gato</option>
            </select>
          </div>
          
          <div class="inputBox">
            <label for="raca" class="labelinput">Raça</label>
            <input type="text" name="raca" id="raca" class="inputUser" required />
          </div>
          </div>
          
          <div id="duo">
          <div class="inputBox">
            <label for="porte" class="labelinput">Porte</label>
            <select name="porte" id="porte" required>
              <option value="">Selecione o porte</option>
              <option value="Pequeno">Pequeno</option>
              <option value="Médio">Médio</option>
              <option value="Grande">Grande</option>
            </select>
          </div>

          <div class="inputBox">
            <label for="situacao" class="labelinput">Situação</label>
            <select name="situacao" id="situacao">
              <option value="Nenhum">Nenhum</option>
              <option value="Vacinado">Vacinado</option>
              <option value="Vacinado e Castrado">Vacinado e Castrado</option>
              <option value="Castrado">Castrado</option>
            </select>
          </div>
          </div>
          </div>

          <div class="inputBox">
            <label for="imagem" class="labelinput">Insira uma foto do Animal</label>
            <input type="file" name="foto" id="foto" class="inputUser" accept="image/*" required>
          </div>

          <p class="link">
            Ao clicar no botão você estará automaticamente concordando com os nossos
            <a href="#">Termo de Uso e Política de Privacidade</a> do site Adote Fácil
          </p>

          <button type="submit">Salvar</button>
        </fieldset>
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