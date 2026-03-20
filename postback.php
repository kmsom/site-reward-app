<?php
require 'config.php'; session_start();
$uid = $_SESSION['user_id']; $mid = $_GET['m'];
// Evita fraude de refresh
$stmt = $pdo->prepare("INSERT INTO missoes_concluidas (usuario_id, missao_id) VALUES (?,?)");
if($stmt->execute([$uid, $mid])) {
    $pdo->prepare("UPDATE usuarios SET saldo_pontos = saldo_pontos + 0.50 WHERE id = ?")->execute([$uid]);
    header("Location: dashboard.php?sucesso=1");
}
