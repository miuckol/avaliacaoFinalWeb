<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="STYLE.css">
    </head>
    <body>

 <header>
        <h1 class="texto-cabecalho">Jogo Digitação/h1>

        <a href="#">Pontuação</a>
    </header>

    <main id="formulário">

        <form>
        
        <h1 class="titulo-forms">Formulário de cadastro</h1>

<label>Nome:</label>            
<input placeholder="Digite seu nome:" type="text">
<span class="error">* <?php echo $nome;?></span>

<label>E-mail:</label>            
<input placeholder="Digite seu e-mail:" type="email">

<label>Senha:</label>            
<input placeholder="Crie sua senha:" type="password">

<label>Confirmar senha:</label>            
<input placeholder="Confirme a senha:" type="password">

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
        <input type="email" placeholder="Digite seu e-mail">

        <label>Senha</label>
        <input type="password" placeholder="Digite sua senha">

        <button>Entrar</button>

    </div>

</div>



        <script src="modal.js"></script>
    </body>
</html>