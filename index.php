<?php
require_once 'conexao.php';

$cadastrar = [];
$sql = "SELECT id, nome, email, senha FROM cadastrar ORDER BY id DESC";

$equipe = [];
$sql = "SELECT idEq, nomeEq, senhaEq, pontuacao FROM equipe ORDER BY idEq DESC";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result) {
        $cadastrar = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $equipe = $cadastrar;
    }
    
    mysqli_stmt_close($stmt);
} else {
    die("Erro ao preparar a consulta: " . mysqli_error($conn));
}
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

        <a href="forms.php">Login/Cadastrar</a>
    </header>

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

    <!-- ÁREA DE EQUIPE -->
<div class="equipe-container">

    <h2 class="titulo-equipe">Equipe</h2>

    <label>Nome da Equipe</label>
    <input type="text" id="nomeEquipe" placeholder="Digite o nome da equipe">

    <label>Código da Equipe</label>
    <input type="text" id="codigoEquipe" placeholder="Digite o código">

    <div class="botoes-equipe">
        <button id="btnCriarEquipe">Criar Equipe</button>
        <button id="btnEntrarEquipe">Entrar em Equipe</button>
    </div>

</div>

<!-- RANKING -->
<div class="ranking-container">

    <h2>Ranking da Equipe</h2>

    <div class="ranking-item">🥇 1º Lugar</div>
    <div class="ranking-item">🥈 2º Lugar</div>
    <div class="ranking-item">🥉 3º Lugar</div>

</div>

    <script src="SCRIPT.js"></script>

</body>
</html>