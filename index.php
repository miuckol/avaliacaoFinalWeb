<?php
session_start();
require_once 'conexao.php';

// Redireciona para login se não estiver autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: forms.php");
    exit;
}

$mensagemSucesso = '';
if (isset($_SESSION['sucesso'])) {
    $mensagemSucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}

//Criar Equipe
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao'])) {

   if ($_POST['acao'] == 'criar_equipe') {
    $nomeEq = $_POST['nomeEq'];
    $codigoEq = $_POST['codigoEq'];

    if (empty($nomeEq) || empty($codigoEq)) {
        $_SESSION['sucesso'] = 'Preencha o nome da equipe';
    } else { // verifa se o cod ja existe
        $codigoEq = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));

        $sql = "INSERT INTO equipe (nomeEq, codigoEq) VALUES (?, ?)";
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
        $_SESSION['sucesso'] = "Equipe \"$nomeEq\" criada! Código: $codigoEq";

    }
        // voltar
    header("Location: index.php");
    exit;
}

// entrar na equipe
    if ($_POST['acao'] == 'entrar_equipe') {
        $codigoEq = trim(strtoupper($_POST['codigoEq'] ?? ''));

        if (empty($codigoEq)) {
            $_SESSION['sucesso'] = 'Informe o codigo da equipe.';
        } else {
            $sql = "SELECT idEq , nomeEq FROM equipe WHERE codigoEq = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $codigoEq);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){
                $idEquipe = $row['idEq'];
                $nomeEq = $row['nomeEq'];
                $idUsuario = $_SESSION['usuario_id'];

            // 2. vincular usuário à equipe
                $sql2 = "UPDATE cadastrar SET equipe_id = ? WHERE id = ?";
                $stmt2 = mysqli_prepare($conn, $sql2);
                mysqli_stmt_bind_param($stmt2, "ii", $idEquipe, $idUsuario);
                mysqli_stmt_execute($stmt2);

                $_SESSION['sucesso'] = "Entrou na equipe com sucesso!";
            } else {
                $_SESSION['sucesso'] = "Código da equipe não encontrado.";
            }
            mysqli_stmt_close($stmt);
        }
    header("Location: index.php");
    exit;
    }
}

// dados usuario
$idUsuario = $_SESSION["usuario_id"];

// Equipe usuario
$equipe = null;
$sql = "SELECT e.nomeEq, e.codigoEq, e.pontuacao
        FROM equipe e
        JOIN cadastrar c ON c.equipe_id = e.idEq
        WHERE c.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);    
if($row = mysqli_fetch_assoc($result)){
    $equipe = $row;
}
mysqli_stmt_close($stmt);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Digitação</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1 class="texto-cabecalho">Jogos Digitação</h1>
    <div class="menu-logado">
        👤<?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>
        <a href="sair.php">Sair</a>
    </div>

</header>

<?php if ($mensagemSucesso): ?>
    <div id="mensagem-sucesso">
        👤 <?php echo $mensagemSucesso; ?>
    </div>
<?php endif; ?>

<main>
    <h1>Curso de Digitação</h1>
    <h3>Clique abaixo para começar:</h3>
    <button id="btnComecar"> ▶ Começar</button>
</main>

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
        <p>🌟 Pontuação: <span id="pontuacaoFinal"></span></p>
    </div>

    <button id="btnReiniciar" class="oculto">Jogar Novamente</button>
</div>

<div class="equipe-wrapper">

    <?php if ($equipe): ?>
    <div class="equipe-container equipe">   
        <h2 class="titulo-equipe">Minha Equipe</h2>
        <p><strong><?php echo htmlspecialchars($equipe['nomeEq']); ?> </strong></p>
        <p>Codigo: <code><?php echo htmlspecialchars($equipe['codigoEq']); ?></code></p>
        <p>Pontuação <?php echo (int)$equipe['pontuacao']; ?></p>
        <a href="ranking.php?liga=1" class="btn-ranking"> Ver ranking da liga</a>
    </div>

    <?php else: ?>
    <div class="equipe-container">
        <h2 class="titulo-equipe">Equipes</h2>
        
        <form action="index.php" method="POST">
            <fieldset>
                <legend>Criar uma equipe</legend>
                <input type="hidden" name="acao" value="criar_equipe">

                <label>Nome da Equipe</label>
                <input type="text" name="nomeEq" placeholder="Digite o nome da equipe"><br>
                <label>Código da Equipe</label>
                <input type="text" name="codigoEq" placeholder="Digite o código" >


                <button type="submit" name="criar_equipe">Criar Equipe</button>
            </fieldset>

            <fieldset>
                <legend>Entrar em uma equipe</legend>
                <input type="hidden" name="acao" value="entrar_equipe">

                <label>Nome da Equipe</label>
                <input type="text" name="nomeEq" placeholder="Digite o nome da equipe"><br>
                <label>Código da Equipe</label>
                <input type="text" name="codigoEq" placeholder="Digite o código" >

                <button type="submit" name="entrar_equipe">Entrar em Equipe</button>
            </fieldset>
        </form>
    </div>

<?php endif; ?>
</div>
<script>
    const USUARIO_LOGADO = true; // php garante que esta logado
</script>
<script src="script.js"></script>

<script>
    setTimeout(() => {
        const msg = document.getElementById('mensagem-sucesso');
        if (msg) msg.style.display = 'none';
    }, 4000);
</script>

</body>
</html>