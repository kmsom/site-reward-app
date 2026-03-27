<?php
session_start();
require 'config.php';

// 1. Verificações de Segurança (Sessão, Token e Tempo)
if (!isset($_SESSION['user_id']) || !isset($_GET['token'])) {
    die("Acesso negado: Token ausente.");
}

// Verifica se o token na URL é o mesmo gerado no ads_view.php
if ($_GET['token'] !== $_SESSION['ad_token']) {
    die("Erro: Token expirado ou inválido. Tente novamente.");
}

// Verifica se o tempo mínimo (10 segundos) foi respeitado no servidor
$tempo_minimo = 10;
$tempo_decorrido = time() - $_SESSION['ad_start_time'];

if ($tempo_decorrido < $tempo_minimo) {
    die("Erro: Você tentou coletar a recompensa rápido demais.");
}

// Destrói o token para evitar que o usuário dê F5 e ganhe de novo
unset($_SESSION['ad_token']);
unset($_SESSION['ad_start_time']);

$uid = $_SESSION['user_id'];
$mid = $_GET['m'] ?? '1';

try {
    // 2. VERIFICAÇÃO DE DUPLICIDADE NO BANCO (Evita ganhar 2x na mesma missão/dia)
    $check_sql = "SELECT id FROM missoes_concluidas 
                  WHERE usuario_id = ? AND missao_id = ? 
                  AND DATE(data_conclusao) = CURRENT_DATE";
    
    $check = $pdo->prepare($check_sql);
    $check->execute([$uid, $mid]);

    if ($check->rowCount() == 0) {
        
        $pdo->beginTransaction();

        // 3. ATUALIZA SALDO (Alterado para + 0.20 COINS)
        $sql_saldo = "UPDATE usuarios SET saldo_pontos = saldo_pontos + 0.20 WHERE id = ?";
        $pdo->prepare($sql_saldo)->execute([$uid]);

        // 4. REGISTRA CONCLUSÃO
        $sql_reg = "INSERT INTO missoes_concluidas (usuario_id, missao_id, data_conclusao) 
                    VALUES (?, ?, NOW())";
        $pdo->prepare($sql_reg)->execute([$uid, $mid]);

        $pdo->commit();
        
        
        // Em vez de apenas exibir o texto, vamos mandar de volta para missoes.php após 2 segundos
        echo "<!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='refresh' content='2;url=missoes.php'>
            <script src='https://cdn.tailwindcss.com'></script>
        </head>
        <body class='bg-[#0a0a1a] text-white flex flex-col items-center justify-center min-h-screen'>
            <div class='text-center p-10 bg-[#16162a] rounded-[2.5rem] border border-[#00dcaa]/30 shadow-2xl'>
                <div class='text-5xl mb-4'>✅</div>
                <h1 class='text-xl font-black text-[#00dcaa] mb-2'>RECOMPENSA CREDITADA!</h1>
                <p class='text-gray-400 text-sm'>Você ganhou 0.20 Coins.</p>
                <p class='text-[10px] text-gray-600 mt-4 uppercase tracking-widest'>Redirecionando...</p>
            </div>
        </body>
        </html>";
        exit;

    } else {
        echo "Você já concluiu esta missão hoje.";
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    http_response_code(500);
    echo "Erro técnico: Contate o suporte.";
}
