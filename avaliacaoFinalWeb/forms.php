<?php
session_start();
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['acao'] == 'cadastro')  {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmacao = $_POST['confirmar_senha'];

        if ($senha !== $confirmacao) {
            $erro = "As senhas não coincidem.";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO cadastrar (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $senha_hash);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit;
        } else {
            echo "Erro ao inserir: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['acao'] == 'login') {

   

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT id, nome, senha FROM cadastrar WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario_nome'] = $row['nome'];
            $_SESSION['sucesso'] = "Login realizado com sucesso!";
            header("Location: index.php");
            exit;
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
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

        <form method="POST" action=forms.php>
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
        
        <h1 class="titulo-forms">Formulário de cadastro</h1>

    <?php if (isset($erro)): ?>
        <p class="erro"><?php echo $erro; ?></p>
    <?php endif; ?>

    <?php if (isset($sucesso)): ?>
        <p class="sucesso"><?php echo $sucesso; ?></p>
    <?php endif; ?>

<label>Nome:</label>            
<input placeholder="Digite seu nome:" name="nome" type="text" required>

<label>E-mail:</label>            
<input placeholder="Digite seu e-mail:" name="email" type="email" required>

<label>Senha:</label>            
<input placeholder="Crie sua senha:" name="senha" type="password" required>

<label>Confirmar senha:</label>            
<input placeholder="Confirme a senha:" name="confirmar_senha" type="password" required>

<button type="submit">Cadastrar</button>

<input type="hidden" name="acao" value="cadastro">

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

        <form method="post" action="forms.php">

            <label>E-mail</label>
            <input type="email" name="email" placeholder="Digite seu e-mail" required>

            <label>Senha</label>
            <input type="password" name="senha" placeholder="Digite sua senha" accept="">

            <button type="submit">Entrar</button>
<input type="hidden" name="acao" value="login">
        </form>
    </div>
</div>

        <script src="modal.js"></script>
    </body>
</html>