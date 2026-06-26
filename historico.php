<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: forms.php");
    exit;
}

$idUsuario = (int) $_SESSION['usuario_id'];

// Busca todas as partidas do usuário, mais recentes primeiro
$partidas = [];
$stmt = mysqli_prepare($conn,
    "SELECT wpm, precisao, erros, tempo, pontuacao, jogado_em
     FROM partida
     WHERE usuario_id = ?
     ORDER BY jogado_em DESC"
);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$partidas = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Estatísticas resumidas
$totalPartidas  = count($partidas);
$totalPontos    = array_sum(array_column($partidas, 'pontuacao'));
$melhorWpm      = $totalPartidas ? max(array_column($partidas, 'wpm'))      : 0;
$melhorPrecisao = $totalPartidas ? max(array_column($partidas, 'precisao')) : 0;

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico – Jogo de Digitação</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1 class="texto-cabecalho"><a href="index.php">Jogo de Digitação</a></h1>
    <nav class="menu-logado">
        <span>👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
        <a href="ranking.php">🏆 Ranking</a>
        <a href="index.php">Jogar</a>
    </nav>
</header>

<main class="ranking-pagina">
    <h1>📋 Meu Histórico</h1>

    <div class="historico-resumo">
        <div class="resumo-card">
            <span class="resumo-valor"><?= $totalPartidas ?></span>
            <span class="resumo-label">Partidas jogadas</span>
        </div>
        <div class="resumo-card">
            <span class="resumo-valor"><?= $totalPontos ?></span>
            <span class="resumo-label">Pontos acumulados</span>
        </div>
        <div class="resumo-card">
            <span class="resumo-valor"><?= $melhorWpm ?></span>
            <span class="resumo-label">Melhor WPM</span>
        </div>
        <div class="resumo-card">
            <span class="resumo-valor"><?= number_format((float)$melhorPrecisao, 1) ?>%</span>
            <span class="resumo-label">Melhor precisão</span>
        </div>
    </div>

    <?php if (empty($partidas)): ?>
        <p class="ranking-vazio">Você ainda não jogou nenhuma partida. <a href="index.php">Jogar agora!</a></p>
    <?php else: ?>
    <div class="ranking-abas-secao">
        <table class="tabela-ranking tabela-historico">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Data / Hora</th>
                    <th>WPM</th>
                    <th>Precisão</th>
                    <th>Erros</th>
                    <th>Tempo (s)</th>
                    <th>Pontuação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partidas as $i => $p): ?>
                <tr>
                    <td><?= $totalPartidas - $i ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($p['jogado_em'])) ?></td>
                    <td><?= (int) $p['wpm'] ?></td>
                    <td><?= number_format((float) $p['precisao'], 1) ?>%</td>
                    <td><?= (int) $p['erros'] ?></td>
                    <td><?= number_format((float) $p['tempo'], 2) ?></td>
                    <td><strong><?= (int) $p['pontuacao'] ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</main>

</body>
</html>