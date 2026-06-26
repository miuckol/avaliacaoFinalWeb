<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: forms.php");
    exit;
}

$idUsuario = (int) $_SESSION['usuario_id'];

$inicioSemana = "DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)";

// Equipe do usuário logado
$minhaEquipe = null;
$stmt = mysqli_prepare($conn,
    "SELECT e.idEq, e.nomeEq, e.codigoEq
     FROM equipe e
     JOIN cadastrar c ON c.equipe_id = e.idEq
     WHERE c.id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$minhaEquipe = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$ligaSelecionada = isset($_GET['liga']) ? (int) $_GET['liga'] : ($minhaEquipe ? (int) $minhaEquipe['idEq'] : null);

// ── Ranking geral de EQUIPES ─────────────────────────────────────────────────
$rankingEquipesTotal = [];
$res = mysqli_query($conn, "SELECT idEq, nomeEq, pontuacao FROM equipe ORDER BY pontuacao DESC");
while ($row = mysqli_fetch_assoc($res)) $rankingEquipesTotal[] = $row;

$rankingEquipesSemana = [];
$res = mysqli_query($conn,
    "SELECT e.idEq, e.nomeEq, COALESCE(SUM(p.pontuacao),0) AS pontuacao
     FROM equipe e
     LEFT JOIN cadastrar c ON c.equipe_id = e.idEq
     LEFT JOIN partida p ON p.usuario_id = c.id AND p.jogado_em >= $inicioSemana
     GROUP BY e.idEq, e.nomeEq
     ORDER BY pontuacao DESC"
);
while ($row = mysqli_fetch_assoc($res)) $rankingEquipesSemana[] = $row;

// ── Ranking geral de JOGADORES (individual) ──────────────────────────────────
$rankingJogadoresTotal = [];
$res = mysqli_query($conn,
    "SELECT c.nome, COALESCE(SUM(p.pontuacao),0) AS pontuacao
     FROM cadastrar c
     LEFT JOIN partida p ON p.usuario_id = c.id
     GROUP BY c.id, c.nome
     ORDER BY pontuacao DESC"
);
while ($row = mysqli_fetch_assoc($res)) $rankingJogadoresTotal[] = $row;

$rankingJogadoresSemana = [];
$res = mysqli_query($conn,
    "SELECT c.nome, COALESCE(SUM(p.pontuacao),0) AS pontuacao
     FROM cadastrar c
     LEFT JOIN partida p ON p.usuario_id = c.id AND p.jogado_em >= $inicioSemana
     GROUP BY c.id, c.nome
     ORDER BY pontuacao DESC"
);
while ($row = mysqli_fetch_assoc($res)) $rankingJogadoresSemana[] = $row;

// ── Ranking da liga (membros da equipe selecionada) ──────────────────────────
$rankingLigaTotal  = [];
$rankingLigaSemana = [];
$nomeLigaSelecionada = null;

if ($ligaSelecionada) {
    $stmt = mysqli_prepare($conn, "SELECT nomeEq FROM equipe WHERE idEq = ?");
    mysqli_stmt_bind_param($stmt, "i", $ligaSelecionada);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    $nomeLigaSelecionada = $row ? $row['nomeEq'] : null;
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn,
        "SELECT c.id, c.nome, COALESCE(SUM(p.pontuacao),0) AS pontuacao
         FROM cadastrar c
         LEFT JOIN partida p ON p.usuario_id = c.id
         WHERE c.equipe_id = ?
         GROUP BY c.id, c.nome
         ORDER BY pontuacao DESC"
    );
    mysqli_stmt_bind_param($stmt, "i", $ligaSelecionada);
    mysqli_stmt_execute($stmt);
    $rankingLigaTotal = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn,
        "SELECT c.id, c.nome, COALESCE(SUM(p.pontuacao),0) AS pontuacao
         FROM cadastrar c
         LEFT JOIN partida p ON p.usuario_id = c.id AND p.jogado_em >= $inicioSemana
         WHERE c.equipe_id = ?
         GROUP BY c.id, c.nome
         ORDER BY pontuacao DESC"
    );
    mysqli_stmt_bind_param($stmt, "i", $ligaSelecionada);
    mysqli_stmt_execute($stmt);
    $rankingLigaSemana = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

function renderTabela(array $linhas, string $colunaNome, string $rotuloNome, int $destacarId = 0): void {
    if (empty($linhas)) {
        echo "<p class='ranking-vazio'>Sem dados ainda.</p>";
        return;
    }
    echo "<table class='tabela-ranking'><thead><tr><th>#</th><th>$rotuloNome</th><th>Pontos</th></tr></thead><tbody>";
    $pos = 1;
    foreach ($linhas as $linha) {
        $cls = (isset($linha['id']) && $linha['id'] == $destacarId) ? ' class="linha-destaque"' : '';
        echo "<tr$cls>";
        echo "<td>$pos</td>";
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
    <title>Ranking – Jogo de Digitação</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1 class="texto-cabecalho"><a href="index.php">Jogo de Digitação</a></h1>
    <nav class="menu-logado">
        <span>👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
        <a href="historico.php">📋 Histórico</a>
        <a href="index.php">Voltar</a>
    </nav>
</header>

<main class="ranking-pagina">
    <h1>🏆 Ranking</h1>

    <!-- GERAL POR EQUIPES -->
    <div class="ranking-abas-secao">
        <h2>Geral — Equipes</h2>
        <div class="ranking-abas">
            <button class="aba-btn aba-ativa" data-target="eq-total">Total</button>
            <button class="aba-btn"           data-target="eq-semana">Semanal</button>
        </div>
        <div id="eq-total"  class="ranking-painel"><?php renderTabela($rankingEquipesTotal,  'nomeEq', 'Equipe'); ?></div>
        <div id="eq-semana" class="ranking-painel oculto"><?php renderTabela($rankingEquipesSemana, 'nomeEq', 'Equipe'); ?></div>
    </div>

    <!-- GERAL INDIVIDUAL -->
    <div class="ranking-abas-secao">
        <h2>Geral — Jogadores</h2>
        <div class="ranking-abas">
            <button class="aba-btn aba-ativa" data-target="jog-total">Total</button>
            <button class="aba-btn"           data-target="jog-semana">Semanal</button>
        </div>
        <div id="jog-total"  class="ranking-painel"><?php renderTabela($rankingJogadoresTotal,  'nome', 'Jogador', $idUsuario); ?></div>
        <div id="jog-semana" class="ranking-painel oculto"><?php renderTabela($rankingJogadoresSemana, 'nome', 'Jogador', $idUsuario); ?></div>
    </div>

    <!-- LIGA -->
    <?php if ($ligaSelecionada && $nomeLigaSelecionada): ?>
    <div class="ranking-abas-secao">
        <h2>Liga: <?= htmlspecialchars($nomeLigaSelecionada) ?></h2>
        <div class="ranking-abas">
            <button class="aba-btn aba-ativa" data-target="liga-total">Total</button>
            <button class="aba-btn"           data-target="liga-semana">Semanal</button>
        </div>
        <div id="liga-total"  class="ranking-painel"><?php renderTabela($rankingLigaTotal,  'nome', 'Jogador', $idUsuario); ?></div>
        <div id="liga-semana" class="ranking-painel oculto"><?php renderTabela($rankingLigaSemana, 'nome', 'Jogador', $idUsuario); ?></div>
    </div>
    <?php else: ?>
    <p class="ranking-vazio">Você ainda não está em nenhuma liga. <a href="index.php">Crie ou entre em uma</a>.</p>
    <?php endif; ?>
</main>

<script>
    document.querySelectorAll('.ranking-abas-secao').forEach(secao => {
        secao.querySelectorAll('.aba-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                secao.querySelectorAll('.aba-btn').forEach(b => b.classList.remove('aba-ativa'));
                btn.classList.add('aba-ativa');
                secao.querySelectorAll('.ranking-painel').forEach(p => p.classList.add('oculto'));
                document.getElementById(btn.dataset.target).classList.remove('oculto');
            });
        });
    });
</script>

</body>
</html>