<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: forms.php");
    exit;
}

$idUsuario  = (int) $_SESSION['usuario_id'];
$ligaSelecionada = isset($_GET['liga']) ? (int) $_GET['liga'] : null;

// Início da semana atual (segunda-feira 00:00)
$inicioSemana = "DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)";

// Equipe do usuário logado (para destacar e exibir link "minha equipe")
$minhaEquipe = null;
$sql = "SELECT e.idEq, e.nomeEq, e.codigoEq
        FROM equipe e
        JOIN cadastrar c ON c.equipe_id = e.idEq
        WHERE c.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($res)) {
    $minhaEquipe = $row;
}
mysqli_stmt_close($stmt);

if ($ligaSelecionada === null && $minhaEquipe) {
    $ligaSelecionada = (int) $minhaEquipe['idEq'];
}

// Ranking geral - total (todas as equipes, desde a criação do sistema)
$rankingGeralTotal = [];
$sql = "SELECT idEq, nomeEq, pontuacao FROM equipe ORDER BY pontuacao DESC";
$res = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    $rankingGeralTotal[] = $row;
}

// Ranking geral - semanal (soma de pontos das partidas da semana, por equipe)
$rankingGeralSemana = [];
$sql = "SELECT e.idEq, e.nomeEq, COALESCE(SUM(p.pontuacao), 0) AS pontuacao
        FROM equipe e
        LEFT JOIN cadastrar c ON c.equipe_id = e.idEq
        LEFT JOIN partida p ON p.usuario_id = c.id AND p.jogado_em >= $inicioSemana
        GROUP BY e.idEq, e.nomeEq
        ORDER BY pontuacao DESC";
$res = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($res)) {
    $rankingGeralSemana[] = $row;
}

// Ranking da liga selecionada (membros da equipe) - total e semanal
$rankingLigaTotal = [];
$rankingLigaSemana = [];
$nomeLigaSelecionada = null;

if ($ligaSelecionada) {
    $sql = "SELECT nomeEq FROM equipe WHERE idEq = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $ligaSelecionada);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $nomeLigaSelecionada = $row['nomeEq'];
    }
    mysqli_stmt_close($stmt);

    // Total por membro (desde a criação)
    $sql = "SELECT c.id, c.nome, COALESCE(SUM(p.pontuacao), 0) AS pontuacao
            FROM cadastrar c
            LEFT JOIN partida p ON p.usuario_id = c.id
            WHERE c.equipe_id = ?
            GROUP BY c.id, c.nome
            ORDER BY pontuacao DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $ligaSelecionada);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $rankingLigaTotal[] = $row;
    }
    mysqli_stmt_close($stmt);

    // Semanal por membro
    $sql = "SELECT c.id, c.nome, COALESCE(SUM(p.pontuacao), 0) AS pontuacao
            FROM cadastrar c
            LEFT JOIN partida p ON p.usuario_id = c.id AND p.jogado_em >= $inicioSemana
            WHERE c.equipe_id = ?
            GROUP BY c.id, c.nome
            ORDER BY pontuacao DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $ligaSelecionada);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $rankingLigaSemana[] = $row;
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

function renderTabela($linhas, $colunaNome, $rotuloNome) {
    if (empty($linhas)) {
        echo "<p class='ranking-vazio'>Sem dados ainda.</p>";
        return;
    }
    echo "<table class='tabela-ranking'><thead><tr><th>#</th><th>$rotuloNome</th><th>Pontos</th></tr></thead><tbody>";
    $pos = 1;
    foreach ($linhas as $linha) {
        echo "<tr>";
        echo "<td>" . $pos . "</td>";
        echo "<td>" . htmlspecialchars($linha[$colunaNome]) . "</td>";
        echo "<td>" . (int) $linha['pontuacao'] . "</td>";
        echo "</tr>";
        $pos++;
    }
    echo "</tbody></table>";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - Jogo de Digitação</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1 class="texto-cabecalho"><a href="index.php">Jogo de Digitação</a></h1>
    <a href="index.php">Voltar</a>
</header>

<main class="ranking-pagina">
    <h1>🏆 Ranking</h1>

    <div class="ranking-abas-secao">
        <h2>Geral (todas as equipes)</h2>
        <div class="ranking-abas">
            <button class="aba-btn aba-ativa" data-target="geral-total">Total</button>
            <button class="aba-btn" data-target="geral-semana">Semanal</button>
        </div>
        <div id="geral-total" class="ranking-painel">
            <?php renderTabela($rankingGeralTotal, 'nomeEq', 'Equipe'); ?>
        </div>
        <div id="geral-semana" class="ranking-painel oculto">
            <?php renderTabela($rankingGeralSemana, 'nomeEq', 'Equipe'); ?>
        </div>
    </div>

    <?php if ($ligaSelecionada && $nomeLigaSelecionada): ?>
    <div class="ranking-abas-secao">
        <h2>Liga: <?php echo htmlspecialchars($nomeLigaSelecionada); ?></h2>
        <div class="ranking-abas">
            <button class="aba-btn aba-ativa" data-target="liga-total">Total</button>
            <button class="aba-btn" data-target="liga-semana">Semanal</button>
        </div>
        <div id="liga-total" class="ranking-painel">
            <?php renderTabela($rankingLigaTotal, 'nome', 'Jogador'); ?>
        </div>
        <div id="liga-semana" class="ranking-painel oculto">
            <?php renderTabela($rankingLigaSemana, 'nome', 'Jogador'); ?>
        </div>
    </div>
    <?php else: ?>
    <p class="ranking-vazio">Você ainda não está em nenhuma equipe/liga. <a href="index.php">Crie ou entre em uma</a> para ver o ranking da sua liga.</p>
    <?php endif; ?>
</main>

<script>
    document.querySelectorAll('.ranking-abas-secao').forEach(secao => {
        const botoes = secao.querySelectorAll('.aba-btn');
        botoes.forEach(botao => {
            botao.addEventListener('click', () => {
                botoes.forEach(b => b.classList.remove('aba-ativa'));
                botao.classList.add('aba-ativa');

                secao.querySelectorAll('.ranking-painel').forEach(p => p.classList.add('oculto'));
                document.getElementById(botao.dataset.target).classList.remove('oculto');
            });
        });
    });
</script>

</body>
</html>