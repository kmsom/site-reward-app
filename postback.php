<?php
require 'config.php';
session_start();

if(!isset($_SESSION['user_id']) || !isset($_GET['m'])) {
    header("Location: dashboard.php");
    exit;
}

$uid = $_SESSION['user_id'];
$mid = $_GET['m'];

try {
    // 1. Dá os R$ 0,50 de teste que você pediu
    $sql = "UPDATE usuarios SET saldo_pontos = saldo_pontos + 0.50 WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uid]);

    // 2. Opcional: Registrar que ele concluiu essa missão específica
    // $pdo->prepare("INSERT INTO missoes_concluidas (usuario_id, missao_id) VALUES (?,?)")->execute([$uid, $mid]);

    // 3. Manda de volta pro dashboard com um aviso de sucesso
    header("Location: dashboard.php?ganhou=0.50");
    exit;

} catch (PDOException $e) {
    die("Erro ao salvar saldo: " . $e->getMessage());
}
