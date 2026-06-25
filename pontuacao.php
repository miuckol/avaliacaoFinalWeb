<?php
session_start();
require_once 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['ok' => false, 'msg' => 'Não autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Método inválido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$usuario_id = (int) $_SESSION['usuario_id'];
$wpm        = isset($data['wpm'])      ? (int)   $data['wpm']      : 0;
$precisao   = isset($data['precisao']) ? (float) $data['precisao'] : 0;
$erros      = isset($data['erros'])    ? (int)   $data['erros']    : 0;
$tempo      = isset($data['tempo'])    ? (float) $data['tempo']    : 0;

if ($wpm < 0 || $wpm > 500 || $precisao < 0 || $precisao > 100 || $erros < 0 || $tempo < 0) {
    echo json_encode(['ok' => false, 'msg' => 'Dados inválidos']);
    exit;
}

// Fórmula de pontuação: WPM × precisão / 10 (ajustável)
$pontuacao = (int) round($wpm * ($precisao / 100) * 10);

$sql  = "INSERT INTO partida (usuario_id, wpm, precisao, erros, tempo, pontuacao) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iididi", $usuario_id, $wpm, $precisao, $erros, $tempo, $pontuacao);

if (mysqli_stmt_execute($stmt)) {
    $sqlEq = "UPDATE equipe e
              JOIN cadastrar c ON c.equipe_id = e.idEq
              SET e.pontuacao = e.pontuacao + ?
              WHERE c.id = ?";
    $stmtEq = mysqli_prepare($conn, $sqlEq);
    mysqli_stmt_bind_param($stmtEq, "ii", $pontuacao, $usuario_id);
    mysqli_stmt_execute($stmtEq);
    mysqli_stmt_close($stmtEq);

    echo json_encode(['ok' => true, 'pontuacao' => $pontuacao]);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Erro ao salvar pontuação']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);