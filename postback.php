<?php
session_start();
require 'config.php';

// 1. Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Erro: Sessão expirada";
    exit;
}

$uid = $_SESSION['user_id'];
$mid = $_GET['m'] ?? '1'; // Pega o ID da missão vindo do JavaScript

try {
    // 2. VERIFICAÇÃO DE SEGURANÇA (PostgreSQL)
    // Usamos CURRENT_DATE para saber se ele já ganhou o bônus hoje
    $check_sql = "SELECT id FROM missoes_concluidas 
                  WHERE usuario_id = ? AND missao_id = ? 
                  AND DATE(data_conclusao) = CURRENT_DATE";
    
    $check = $pdo->prepare($check_sql);
    $check->execute([$uid, $mid]);

    if ($check->rowCount() == 0) {
        
        // Inicia uma transação para garantir que os dois comandos funcionem ou nenhum
        $pdo->beginTransaction();

        // 3. ATUALIZA O SALDO (Os R$ 0,50 de teste)
        $sql_saldo = "UPDATE usuarios SET saldo_pontos = saldo_pontos + 0.50 WHERE id = ?";
        $pdo->prepare($sql_saldo)->execute([$uid]);

        // 4. REGISTRA A CONCLUSÃO (Importante para travar o botão)
        // Certifique-se que sua tabela tenha a coluna 'data_conclusao'
        $sql_reg = "INSERT INTO missoes_concluidas (usuario_id, missao_id, data_conclusao) 
                    VALUES (?, ?, NOW())";
        $pdo->prepare($sql_reg)->execute([$uid, $mid]);

        $pdo->commit();
        echo "Sucesso";

    } else {
        // Se ele tentar clicar de novo via código, o PHP barra aqui
        echo "Ja_Concluida";
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo "Erro no Banco: " . $e->getMessage();
}
