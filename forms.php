<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "INSERT INTO cadastrar (id, nome, email, senha) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $id, $nome, $email, $senha);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Erro ao inserir: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}
   mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="STYLE.css">
    </head>
    <body>

 <header>
         <h1>
            <a href="index.php">Jogo de Digitação</a>
         <h1>

        <a href="#">Pontuação</a>
    </header>

    <main id="formulário">

        <form>
        
        <h1 class="titulo-forms">Formulário de cadastro</h1>

<label>Nome:</label>            
<input placeholder="Digite seu nome:" name="nome" type="text" required>

<label>E-mail:</label>            
<input placeholder="Digite seu e-mail:" name="email" type="email" required>

<label>Senha:</label>            
<input placeholder="Crie sua senha:" name="senha" type="password" required>

<label>Confirmar senha:</label>            
<input placeholder="Confirme a senha:" name="confirmar_senha" type="password" required>

<button type="submit">Cadastrar</button>

<p class="link-login">
    Já possui cadastro?
    <a href="#" id="abrirLogin">Faça login aqui.</a>
</p>
        </form>

    </main>

<div id="overlay" class="login-oculto">

    <div id="modalLogin">

        <span id="fecharModal">&times;</span>
        <h2>Login</h2>

        <label>E-mail</label>
        <input type="email" name="email" placeholder="Digite seu e-mail" required>

        <label>Senha</label>
        <input type="password" name="senha" placeholder="Digite sua senha" accept="">

        <button>Entrar</button>

    </div>

</div>

        <script src="modal.js"></script>
    </body>
</html>