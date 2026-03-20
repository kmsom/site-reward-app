<?php
require 'config.php';
session_start();

if(!isset($_SESSION['user_id'])) { exit; }

$uid = $_SESSION['user_id'];
$mid = $_GET['m'] ?? '1'; // ID da missão vindo do clique

try {
    // 1. VERIFICA SE O USUÁRIO JÁ FEZ ESSA MISSÃO HOJE
    $check = $pdo->prepare("SELECT id FROM missoes_concluidas WHERE usuario_id = ? AND missao_id = ? AND DATE(data_conclusao) = CURDATE()");
    $check->execute([$uid, $mid]);

    if($check->rowCount() == 0) {
        // 2. SE NÃO FEZ, ADICIONA O SALDO DE R$ 0,50
        $update = $pdo->prepare("UPDATE usuarios SET saldo_pontos = saldo_pontos + 0.50 WHERE id = ?");
        $update->execute([$uid]);

        // 3. REGISTRA NA TABELA DE MISSÕES CONCLUÍDAS
        $insert = $pdo->prepare("INSERT INTO missoes_concluidas (usuario_id, missao_id, data_conclusao) VALUES (?, ?, NOW())");
        $insert->execute([$uid, $mid]);

        echo "Sucesso";
    } else {
        echo "Ja_Concluida";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
