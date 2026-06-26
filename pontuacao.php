<?php
ob_start();
session_start();
require_once 'conexao.php';
ob_end_clean();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['ok' => false, 'msg' => 'Não autenticado', 'session' => session_id()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Método inválido']);
    exit;
}

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode(['ok' => false, 'msg' => 'JSON inválido', 'raw' => $raw]);
    exit;
}

$usuario_id = (int) $_SESSION['usuario_id'];
$wpm        = isset($data['wpm'])      ? (int)   $data['wpm']      : 0;
$precisao   = isset($data['precisao']) ? (float) $data['precisao'] : 0.0;
$erros      = isset($data['erros'])    ? (int)   $data['erros']    : 0;
$tempo      = isset($data['tempo'])    ? (float) $data['tempo']    : 0.0;

if ($wpm < 0 || $wpm > 500 || $precisao < 0 || $precisao > 100 || $erros < 0 || $tempo < 0) {
    echo json_encode(['ok' => false, 'msg' => 'Dados fora do intervalo', 'dados' => compact('wpm','precisao','erros','tempo')]);
    exit;
}

$pontuacao = (int) round($wpm * ($precisao / 100) * 10);

// INSERT na tabela partida
$stmt = mysqli_prepare($conn,
    "INSERT INTO partida (usuario_id, wpm, precisao, erros, tempo, pontuacao) VALUES (?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    echo json_encode(['ok' => false, 'msg' => 'Prepare falhou: ' . mysqli_error($conn)]);
    exit;
}

// tipos: usuario_id=i, wpm=i, precisao=d, erros=i, tempo=d, pontuacao=i
mysqli_stmt_bind_param($stmt, "iididi", $usuario_id, $wpm, $precisao, $erros, $tempo, $pontuacao);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    // Atualiza pontuação acumulada da equipe (só se o usuário pertence a uma)
    $sqlEq = "UPDATE equipe e
              JOIN cadastrar c ON c.equipe_id = e.idEq
              SET e.pontuacao = e.pontuacao + ?
              WHERE c.id = ?";
    $stmtEq = mysqli_prepare($conn, $sqlEq);
    if ($stmtEq) {
        mysqli_stmt_bind_param($stmtEq, "ii", $pontuacao, $usuario_id);
        mysqli_stmt_execute($stmtEq);
        mysqli_stmt_close($stmtEq);
    }

    echo json_encode(['ok' => true, 'pontuacao' => $pontuacao]);
} else {
    $err = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    echo json_encode(['ok' => false, 'msg' => 'Execute falhou: ' . $err]);
}

mysqli_close($conn);