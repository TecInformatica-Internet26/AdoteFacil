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
  <link rel="stylesheet" href="../CSS/cadastrar.css" />
  <script src="../JS/cadastrar.js" defer></script>
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
    <div class="box">
      <form action="../PHP/Usuario/cadastrarUser.php" method="post" enctype="multipart/form-data" onsubmit="return validar()">
        <fieldset>
          <legend><b>Cadastre-se</b></legend>

          <div class="inputBox">
            <label for="nome" class="labelinput">Nome completo</label>
            <input type="text" name="nome" id="nome" class="inputUser" required>
          </div>

          <div class="inputBox">
            <label for="email" class="labelinput">E-mail</label>
            <input type="email" name="email" id="email" class="inputUser" required>
            <p>Coloque um e-mail ativo.</p>
          </div>
          
          <div class="inputBox">
            <label for="cpf" class="labelinput">CPF</label>
            <input type="text" name="cpf" id="cpf" class="inputUser" maxlength="14" required 
        oninput="mascaraCPF(this)">
          </div>
          
          <div class="inputBox">
            <label for="dataNasc" class="labelinput">Data de Nascimento</label>
            <input type="date" name="dataNascimento" id="dataNasc" class="inputUser" required>
          </div>

          <div class="inputBox">
            <label for="telefone" class="labelinput">Telefone</label>
            <input type="tel" name="telefone" id="telefone" class="inputUser" required oninput="mascaraTel(this)">
          </div>

          <div class="inputBox">
            <label for="whats" class="labelinput">WhatsApp</label>
            <input type="tel" name="whats" id="whats" class="inputUser" required oninput="mascaraTel(this)">
          </div>

          <div class="scroll">
            <div class="inputBox">
              <label for="estado" class="labelinput">Estado</label>
              <select name="estado" id="estado" onchange="carregarCidades()" required>
                <option value="">Selecione um Estado</option>
                <option value="AC">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AM">Amazonas</option>
                <option value="AP">Amapá</option>
                <option value="BA">Bahia</option>
                <option value="CE">Ceará</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Espírito Santo</option>
                <option value="GO">Goiás</option>
                <option value="MA">Maranhão</option>
                <option value="MG">Minas Gerais</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MT">Mato Grosso</option>
                <option value="PA">Pará</option>
                <option value="PB">Paraíba</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piauí</option>
                <option value="PR">Paraná</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RO">Rondônia</option>
                <option value="RR">Roraima</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="SC">Santa Catarina</option>
                <option value="SE">Sergipe</option>
                <option value="SP">São Paulo</option>
                <option value="TO">Tocantins</option>
              </select>
            </div>

            <div class="inputBox">
              <label for="cidade" class="labelinput">Cidade</label>
              <select name="cidade" id="cidade" disabled required>
                <option value="">Selecione uma Cidade</option>
              </select>
            </div>
          </div>

          <div class="inputBox">
            <label for="senha" class="labelinput">Senha</label>
            <input type="password" name="senha" id="senha" class="inputUser" required>
            <p>Deve ter pelo menos 8 caracteres, incluindo letras e números.</p>
          </div>

          <div class="inputBox">
            <label for="Csenha" class="labelinput">Confirme sua Senha</label>
            <input type="password" name="Csenha" id="Csenha" class="inputUser" required>
          </div>

          <div class="inputBox">
            <label for="foto" class="labelinput">Foto de Perfil</label>
            <input type="file" name="foto" id="foto" accept="imagem/*">
          </div>

          <input type="submit" value="Enviar" class="botao-salvar">
          <p>Ao clicar em "Salvar", você concorda com os nossos <a href="#">Termos de Uso</a> e <a href="#">Política de Privacidade</a>.</p>

          <p class="link-secundario">
          <a href="entrar.php">Já possui uma conta?</a>
          </p>

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