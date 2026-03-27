<?php
session_start();
require 'config.php';

// 1. Bloqueio de Bots (User-Agent e Referer)
if (!isset($_SERVER['HTTP_REFERER']) || !str_contains($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
    die("Acesso Negado: Origem Invalida.");
}

$uid = $_SESSION['user_id'] ?? null;
$token_url = $_GET['token'] ?? '';
$sig_url = $_GET['sig'] ?? '';
$mid = $_GET['m'] ?? '1';
$secret_key = "MEC_STUDIO_TOP_SECRET_99"; // Deve ser IGUAL ao ads_view.php

if (!$uid || !$token_url || !$sig_url) {
    die("Erro: Dados de sessao ausentes.");
}

// 2. Validação da Assinatura Digital (HMAC)
$expected_sig = hash_hmac('sha256', $token_url . $uid . $mid, $secret_key);

if (!hash_equals($expected_sig, $sig_url)) {
    die("Erro: Assinatura de seguranca invalida. Bot detectado?");
}

// 3. Validação de Tempo (Mínimo 10 segundos)
$tempo_decorrido = time() - ($_SESSION['ad_start_time'] ?? 0);
if ($tempo_decorrido < 10) {
    die("Erro: Tempo insuficiente.");
}

// Limpa tokens para evitar reuso
unset($_SESSION['ad_token'], $_SESSION['ad_start_time']);

try {
    $check = $pdo->prepare("SELECT id FROM missoes_concluidas WHERE usuario_id = ? AND missao_id = ? AND DATE(data_conclusao) = CURRENT_DATE");
    $check->execute([$uid, $mid]);

    if ($check->rowCount() == 0) {
        $pdo->beginTransaction();

        // Crédito de 0.50 Coins (Sustentável com seu CPM atual)
        $pdo->prepare("UPDATE usuarios SET saldo_pontos = saldo_pontos + 0.50 WHERE id = ?")->execute([$uid]);
        $pdo->prepare("INSERT INTO missoes_concluidas (usuario_id, missao_id, data_conclusao) VALUES (?, ?, NOW())")->execute([$uid, $mid]);

        $pdo->commit();
        
        // Redirecionamento com Design de Sucesso
        echo "<!DOCTYPE html><html lang='pt-br'><head><meta charset='UTF-8'><meta http-equiv='refresh' content='2;url=missoes.php'><script src='https://cdn.tailwindcss.com'></script></head>
        <body class='bg-[#0a0a1a] text-white flex items-center justify-center min-h-screen'><div class='text-center p-8 bg-[#16162a] rounded-3xl border border-[#00dcaa]/20 shadow-2xl'>
        <div class='text-4xl mb-2'>✅</div><h1 class='text-[#00dcaa] font-black'>COINS CREDITADOS!</h1><p class='text-xs text-gray-500 mt-2 uppercase'>Voltando em 2s...</p></div></body></html>";
    } else {
        echo "Missao ja realizada hoje.";
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die("Erro no banco de dados.");
}
