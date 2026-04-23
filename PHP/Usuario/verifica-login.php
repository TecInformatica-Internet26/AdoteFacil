<?php
    require_once '../conexao.php'; // garante que o arquivo seja iniciado primeiro
    session_start(); //inicia a sessão

    if(isset($_POST['bt-entrar'])): //isset = se existir ação do botão bt-entrar

        $email = $_POST['email'];
        $senha = $_POST['senha'];
        echo "E-mail: ".$email;
        echo "<br>";
        echo "Senha: ".$senha;
        if(empty($email) or empty($senha)): // se $email ou $senha vazia, faça...
            $erro = "Todos os campos devem ser preenchidos..";
            echo "<script>alert('Todos os campos devem ser preenchidos!');</script>"; // Imprime uma alert na tela utilizando script
            echo $erro; //garante que a mensagem de erro seja exibida
            header('Location: ../../Paginas/entrar.php');

        else : // caso haja algo nas duas variáveis tenta efetuar o login
            $sql = "SELECT email FROM cliente WHERE email = '$email'";
            $resultado = $conexao->query($sql);
            print_r($resultado); // imprime o valor da variável na tela

            if(mysqli_num_rows($resultado)>0): // se a quantidade de linhas encontradas for maior que 0 verifica novamente o email e confere a senha
                $sql = "SELECT * FROM cliente WHERE email = '$email' AND senha = '$senha'";
                $resultado = $conexao->query($sql); // caso as informações coincidam, armazena em $resultado
                if(mysqli_num_rows($resultado)==1): // se email e senha "baterem" inicia as sessões
                    $dados = mysqli_fetch_array($resultado);
                    $_SESSION['online'] = true;
                    $_SESSION['nomeUsuario'] = $dados['nome'];
                    $_SESSION['cpfUsuario'] = $dados['cpf'];
                    $_SESSION['DataNascUsuario'] = $dados['data_nasc'];
                    $_SESSION['emailUsuario'] = $dados['email'];
                    $_SESSION['senhaUsuario'] = $dados['senha'];
                    $_SESSION['telefoneUsuario'] = $dados['telefone'];
                    $_SESSION['whatsUsuario'] = $dados['whatsapp'];
                    $_SESSION['estadoUsuario'] = $dados['estado'];
                    $_SESSION['cidadeUsuario'] = $dados['cidade'];
                    $_SESSION['fotoUsuario'] = $dados['foto'];
                    $_SESSION['idUsuario'] = $dados['id_cliente'];
                    header('Location: perfil.php');

                else: //caso email ou senha diferente
                    $erro = "Usuário ou senha não conferem.";
                    echo "<script>alert('Usuário ou senha não conferem.');</script>";
                    header('Location: ../../Paginas/entrar.php');
                    echo $erro; // garante que a mensagem de erro seja exibida
                endif;
            else:
                $erro = "Usuário não encontrado.";
                echo "<script>alert('usuário não encontrado.');</script>"; // Imprime uma alert na tela utilizando script
                header('Location: ../../Paginas/entrar.php');
                echo $erro; //garante que a mensagem de erro seja exibida
            endif;
        endif;
    endif;
?>