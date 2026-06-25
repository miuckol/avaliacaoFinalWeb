<?php
session_start();

$usuarioLogado = '';

$mensagemSucesso = '';

if (isset($_SESSION['sucesso'])) {
    $mensagemSucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}
require_once 'conexao.php';

$dadosEquipe = null;

if(isset($_SESSION['usuario_id'])){

    $sql = "
        SELECT e.idEq, e.nomeEq, e.codigoEq, e.pontuacao
        FROM cadastrar c
        INNER JOIN equipe e ON c.equipe_id = e.idEq
        WHERE c.id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['usuario_id']);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $dadosEquipe = mysqli_fetch_assoc($result);
}

$membrosEquipe = [];

if($dadosEquipe){

    $sql = "SELECT nome FROM cadastrar WHERE equipe_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $dadosEquipe['idEq']);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $membrosEquipe = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

if (isset($_POST['acao']) && $_POST['acao'] == 'sair_equipe') {

    $idUsuario = $_SESSION['usuario_id'];

    $sql = "UPDATE cadastrar SET equipe_id = NULL WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);

    $_SESSION['sucesso'] = "Você saiu da equipe.";

    header("Location: index.php");
    exit;
}

   if (isset($_POST['acao']) && $_POST['acao'] == 'criar_equipe') {

        if(!isset($_SESSION['usuario_id'])){
            header("Location: forms.php");
            exit;
        }

        $nomeEq = $_POST['nomeEq'];

        // gerar código 6 letras
        $codigoEq = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

        // inserir equipe
        $sql = "INSERT INTO equipe (nomeEq, codigoEq, pontuacao) VALUES (?, ?, 0)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $nomeEq, $codigoEq);
        mysqli_stmt_execute($stmt);

        // pegar id da equipe
        $idEquipe = mysqli_insert_id($conn);

        // vincular usuário
        $idUsuario = $_SESSION['usuario_id'];

        $sql2 = "UPDATE cadastrar SET equipe_id = ? WHERE id = ?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "ii", $idEquipe, $idUsuario);
        mysqli_stmt_execute($stmt2);

        // mensagem
        $_SESSION['sucesso'] = "Equipe criada! Código: " . $codigoEq;

        // voltar
        header("Location: index.php");
        exit;
    }
}

    if (isset($_POST['acao']) && $_POST['acao'] == 'entrar_equipe') {

       if(!isset($_SESSION['usuario_id'])){
    header("Location: forms.php");
    exit;
}



$codigoEq = $_POST['codigoEq'];

// 1. procurar equipe pelo código
$sql = "SELECT idEq FROM equipe WHERE codigoEq = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $codigoEq);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if($row = mysqli_fetch_assoc($result)){

    $idEquipe = $row['idEq'];
    $idUsuario = $_SESSION['usuario_id'];

    // 2. vincular usuário à equipe
    $sql2 = "UPDATE cadastrar SET equipe_id = ? WHERE id = ?";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, "ii", $idEquipe, $idUsuario);
    mysqli_stmt_execute($stmt2);

    $_SESSION['sucesso'] = "Entrou na equipe com sucesso!";

} else {
    $_SESSION['sucesso'] = "Código da equipe não existe!";
}

header("Location: index.php");
exit;
    }


$cadastrar = [];
$sql = "SELECT id, nome, email, senha FROM cadastrar ORDER BY id DESC";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        $cadastrar = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    mysqli_stmt_close($stmt);
}

$equipe = [];
$sql = "SELECT idEq, nomeEq, codigoEq, pontuacao FROM equipe ORDER BY idEq DESC";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        $equipe = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Digitação</title>
    <link rel="stylesheet" href="STYLE.css">
</head>
<body>

    <header>
        <h1 class="texto-cabecalho">Jogos Digitação</h1>

      <?php if(isset($_SESSION['usuario_nome'])): ?>

    <div class="menu-logado">
        👤 <?php echo $_SESSION['usuario_nome']; ?>
        <a href="logout.php">Sair</a>
    </div>

<?php else: ?>

    <a href="forms.php">Login/Cadastrar</a>

<?php endif; ?>
</header>


    <?php if (!empty($mensagemSucesso)): ?>
    <div id="mensagem-sucesso">
        <?php echo $mensagemSucesso; ?>
    </div>
<?php endif; ?>

    <main>

        <h1>Curso de Digitação</h1>

        <h3>Clique abaixo para começar:</h3>

        <button id="btnComecar">Começar</button>

    </main>

</div>

    <div id="areaCurso" class="oculto">

        <h2>Digite a frase abaixo:</h2>

        <div id="frase"></div>

        <p id="mensagem"></p>

        <div id="resultado" class="resultado oculto">
    <h2>🏆 Resultado</h2>

    <p>⏱ Tempo: <span id="tempoFinal"></span></p>

    <p>❌ Erros: <span id="errosFinal"></span></p>

    <p>🎯 Precisão: <span id="precisaoFinal"></span></p>

    <p>⚡ WPM: <span id="wpmFinal"></span></p>
</div>

        <button id="btnReiniciar" class="oculto">
    Jogar Novamente
</button>

    </div>

<section id="equipe-ranking" >
 <?php if(!$dadosEquipe): ?>
  <form method="POST" action="index.php">

<div class="equipe-container">

    <h2 class="titulo-equipe">Equipe</h2>

    <label>Nome da Equipe</label>
    <input type="text" name="nomeEq" placeholder="Digite o nome da equipe">

    <label>Código da Equipe</label>
    <input type="password" name="codigoEq" placeholder="Digite o código">

    <div class="botoes-equipe">

        <button type="submit" name="acao" value="criar_equipe">
            Criar Equipe
        </button>

        <button type="submit" name="acao" value="entrar_equipe">
            Entrar em Equipe
        </button>

    </div>

</div>

</form>
<?php endif; ?>
<!-- RANKING -->

<?php if($dadosEquipe): ?>

<div class="ranking-container">

    <h2>Equipe: <?php echo $dadosEquipe['nomeEq']; ?></h2>

    <h3>Código: <?php echo $dadosEquipe['codigoEq']; ?></h3>

    <h3>Membros</h3>

    <?php foreach($membrosEquipe as $membro): ?>

    <p>
        👤 <?php echo $membro['nome']; ?>
    </p>

<?php endforeach; ?>

    <form method="POST">
    <button type="submit" name="acao" value="sair_equipe">
        Sair da Equipe
    </button>
</form>

</div>
<?php endif; ?>

    </section>

<script>
setTimeout(() => {
    const msg = document.getElementById('mensagem-sucesso');

    if(msg){
        msg.style.display = 'none';
    }
}, 3000);
</script>

    <script src="SCRIPT.js"></script>

</body>
</html>