<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('../../conexao.php');

$sql = "SELECT * FROM pet ORDER BY id_pet DESC";
$result = mysqli_query($conexao, $sql);

if ($result) {
    $pets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
} else {
    echo "Erro na consulta: " . mysqli_error($conexao);
    $pets = array();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../CSS/consulta.css">
  <link rel="stylesheet" href="../../../CSS/padrao.css">
  <script src="../../../JS/deleteAdm.js" defer></script>
  <script src="../../../JS/padrao.js" defer></script>
  <title>Consulta de PETs</title>
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
            <h1>PETs Cadastrados</h1>

            <table>
            <thead>
                <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Nome</th>
                <th>Gênero</th>
                <th>Idade</th>
                <th>Espécie</th>
                <th>Porte</th>
                <th>Raça</th>
                <th>Status</th>
                <th>Ações</th>
                </tr>
            </thead>

            <tbody>
            <?php if (count($pets) > 0): ?>
                <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td><?= $pet['id_pet'] ?></td>

                        <td>
                            <img src="../../../IMG/adote/<?= htmlspecialchars($pet['foto']) ?>" width="70">
                        </td>

                        <td><?= htmlspecialchars($pet['nome'] ?? '') ?></td>
                        <td><?= htmlspecialchars($pet['genero'] ?? '') ?></td>
                        <td><?= htmlspecialchars($pet['idade'] ?? '') ?></td>
                        <td><?= htmlspecialchars($pet['especie'] ?? '') ?></td>
                        <td><?= htmlspecialchars($pet['porte'] ?? '') ?></td>
                        <td><?= htmlspecialchars($pet['raca'] ?? '') ?></td>

                        <td>
                            <?= $pet['statusPet'] == 'adotado' ? '🐾 Adotado' : '🟢 Disponível' ?>
                        </td>

                        <!-- AÇÕES 2x2 -->
                        <td class="acoes">

                            <!-- Linha 1 -->
                            <div class="acoes-linha">
                                <a href="editar.php?id=<?= $pet['id_pet']  ?>" class="botao btn-editar">✏ Editar</a>

                                <a href="apagar.php?id_pet=<?= $pet['id_pet'] ?>" 
                                    class="botao btn-apagar btn-delete">🗑 Apagar</a>
                            </div>

                            <!-- Linha 2 -->
                            <div class="acoes-linha">
                                <form action="alterarStatus.php" method="POST">
                                    <input type="hidden" name="id_pet" value="<?= $pet['id_pet'] ?>">

                                    <select name="status" class="select-status">
                                        <option value="disponivel" <?= $pet['statusPet']=='disponivel'?'selected':'' ?>>
                                            🟢 Disponível
                                        </option>
                                        <option value="adotado" <?= $pet['statusPet']=='adotado'?'selected':'' ?>>
                                            🐾 Adotado
                                        </option>
                                    </select>

                                    <button type="submit" class="botao btn-salvar">💾 Salvar</button>
                                </form>
                            </div>

                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="13">Nenhum pet cadastrado.</td></tr>
            <?php endif; ?>
            </tbody>
            </table>

            <div class="back-button">
            <a href="../../../index.php">Voltar</a>
            <a href="../Usuario/consulta.php">Consultar Humano</a>
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
</body>
</html>
