<?php
session_start();
require '../conexao.php';


if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../Paginas/entrar.php');
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT * FROM cliente WHERE id_cliente = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Usuário não encontrado.");
}

$usuario = $result->fetch_assoc();

// Busca os pets do usuário
$sqlPets = "SELECT * FROM pet WHERE id_cliente = ?";
$stmtPets = $conexao->prepare($sqlPets);
$stmtPets->bind_param("i", $id_usuario);
$stmtPets->execute();
$resultPets = $stmtPets->get_result();

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
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../JS/delete.js" defer></script>
    <link rel="icon" href="../../IMG/icones/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../CSS/perfil.css">
    <link rel="stylesheet" href="../../CSS/padrao.css">
    <script src="../../JS/padrao.js" defer></script>
    <title>Perfil</title>
    <style>

    </style>
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
                    <li class="li-dropdown linkIndex"><a href="../../index.php">Início</a></li>
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
                            <?php
                                $foto = $_SESSION['usuario_foto'] ?? '';
                                $nome = $_SESSION['usuario_nome'] ?? 'Usuário';

                                $partes = explode(' ', trim($nome));
                                $iniciais = strtoupper($partes[0][0] . ($partes[1][0] ?? ''));
                                ?>
                                                        
                                <?php if (!empty($foto)): ?>
                                    <img src="../../IMG/usuario/<?php echo $foto; ?>" class="foto-perfil" alt="Foto">
                                <?php else: ?>
                                    <div class="foto-inicial"><?php echo $iniciais; ?></div>
                            <?php endif; ?>

                            <div class="dropdown-user">
                                <span class="nome-dropdown">
                                    <?php echo explode(" ", $_SESSION['usuario_nome'])[0]; ?>
                                </span>

                                <a href="perfil.php" class="link-perfil">Perfil</a>
                                <a href="logout.php" class="link-perfil">Sair</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container">
            <h1>Perfil do Usuário</h1>

        <div class="">
            <?php
                $foto = $usuario['foto'] ?? '';
                $nome = $usuario['nome'] ?? 'Usuário';

                // gerar iniciais
                $partes = explode(' ', trim($nome));
                $iniciais = strtoupper($partes[0][0] . ($partes[1][0] ?? ''));
                ?>

                <?php if (!empty($foto)): ?>
                    <img src="../../IMG/usuario/<?= htmlspecialchars($foto) ?>" 
                        alt="Foto do perfil" class="fotoPerfil preview-foto" >
                <?php else: ?>
                    <div class="foto-inicial-perfil"><?= $iniciais ?></div>
                <?php endif; ?>
        </div>
        <div class="info">
            <strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?>
        </div>
        <div class="info">
            <strong>CPF:</strong> <?= formatarCPF($usuario['cpf']) ?>  
        </div>
        
        <div class="info">
            <strong>Data de Nascimento:</strong> <?= htmlspecialchars($usuario['data_nasc']) ?>
        </div>
        
        <div class="info">
            <strong>E-mail:</strong> <?= htmlspecialchars($usuario['email']) ?>
        </div>
        <div class="numeros">
            <div class="info tel">
            <strong>Telefone:</strong> <?= formatarTelefone($usuario['telefone']) ?>
            </div>
            <div class="info zap">
                <strong>WhatsApp:</strong> <?= formatarTelefone($usuario['whatsapp']) ?>
            </div>
        </div>
        
        <div class="locais">
            <div class="info estado">
                <strong>Estado:</strong> <p><?= htmlspecialchars($usuario['estado']) ?></p>
            </div>
            <div class="info cidade">
                <strong>Cidade:</strong> <p><?= htmlspecialchars($usuario['cidade']) ?></p>
            </div>
        </div>
        
        <div id="registrar">
            <a href="../../index.php" class="btn btn-primary">Sair</a>
            <a href="editar.php" class="btn btn-primary">Editar</a>
            <form action="delete.php" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar sua conta?');">
                <button type="submit">Deletar</button>
            </form>
        </div>
    </div>

    

    <div class="pets-container">
        <h2>Meus Pets Cadastrados</h2>
        <?php if ($resultPets->num_rows > 0): ?>
            <?php while ($pet = $resultPets->fetch_assoc()): ?>
                <div class="pet-card">
                    <img src="../../IMG/adote/<?= htmlspecialchars($pet['foto']) ?>" class="pet-img">
 
                    <h3><?= htmlspecialchars($pet['nome']) ?></h3>
                    <p><strong>Raça:</strong> <?= htmlspecialchars($pet['raca']) ?></p>
                    <p><strong>Idade:</strong> <?= htmlspecialchars($pet['idade']) ?></p>
                    <p><strong>Status:</strong> 
                        <?= $pet['statusPet'] === 'adotado' ? '🐾 Adotado' : '🟢 Disponível' ?>
                    </p>

                    <!-- Form para alterar o status -->
                    <form action="../PETs/alterarStatus.php" method="POST">
                        <input type="hidden" name="id_pet" value="<?= $pet['id_pet'] ?>">

                        <select name="status">
                            <option value="disponivel" <?= $pet['statusPet']=='disponivel'?'selected':'' ?>>
                                Disponível
                            </option>
                            <option value="adotado" <?= $pet['statusPet']=='adotado'?'selected':'' ?>>
                                Adotado
                            </option>
                        </select>
                        
                        <button type="submit">Atualizar</button>
                        
                    </form>
                    <a href="../PETs/editarPet.php?id=<?= $pet['id_pet'] ?>" class="buttonEditarpet"><button>Editar</button></a>
                    <form action="../PETs/deletePet.php" method="POST"
                        onsubmit="return confirm('Tem certeza que deseja deletar seu pet?');">
                        <input type="hidden" name="id_pet" value="<?= $pet['id_pet'] ?>">
                        <button type="submit" style="max-width: 176px; margin: auto;">Deletar</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum pet cadastrado ainda.</p>
        <?php endif; ?>
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
<script src="https://unpkg.com/imask"></script>

<script>
    // Máscara CPF
    const cpf = document.getElementById('cpf');
    if (cpf) {
        IMask(cpf, { mask: '000.000.000-00' });
    }

    // Máscara Telefone
    const telefone = document.getElementById('telefone');
    if (telefone) {
        IMask(telefone, {
            mask: '(00) 00000-0000'
        });
    }

    // Máscara CEP
    const cep = document.getElementById('cep');
    if (cep) {
        IMask(cep, { mask: '00000-000' });
    }
</script>
</body>
</html>