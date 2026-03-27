<?php
session_start();
require 'config.php';

// 1. Verificações de Segurança (Sessão, Token e Tempo)
if (!isset($_SESSION['user_id']) || !isset($_GET['token'])) {
    die("Acesso negado: Token ausente.");
}

if ($_GET['token'] !== $_SESSION['ad_token']) {
    die("Erro: Token expirado ou inválido. Tente novamente.");
}

// Verifica se passaram pelo menos 10 segundos desde o clique no ads_view
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
    // 2. VERIFICAÇÃO DE DUPLICIDADE NO BANCO
    $check_sql = "SELECT id FROM missoes_concluidas 
                  WHERE usuario_id = ? AND missao_id = ? 
                  AND DATE(data_conclusao) = CURRENT_DATE";
    
    $check = $pdo->prepare($check_sql);
    $check->execute([$uid, $mid]);

    if ($check->rowCount() == 0) {
        
        $pdo->beginTransaction();

        // 3. ATUALIZA SALDO
        $sql_saldo = "UPDATE usuarios SET saldo_pontos = saldo_pontos + 0.50 WHERE id = ?";
        $pdo->prepare($sql_saldo)->execute([$uid]);

        // 4. REGISTRA CONCLUSÃO
        $sql_reg = "INSERT INTO missoes_concluidas (usuario_id, missao_id, data_conclusao) 
                    VALUES (?, ?, NOW())";
        $pdo->prepare($sql_reg)->execute([$uid, $mid]);

        $pdo->commit();
        echo "Sucesso: Recompensa creditada!";

    } else {
        echo "Você já concluiu esta missão hoje.";
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    http_response_code(500);
    echo "Erro técnico: Contate o suporte.";
}
