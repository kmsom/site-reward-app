<?php
// Configurações do seu Supabase (Pegue no painel Settings -> API)
$supabaseUrl = 'https://SUA_URL_AQUI.supabase.co';
$supabaseKey = 'SUA_SERVICE_ROLE_KEY_AQUI'; // Use a Service Role para permissão de escrita

// 1. Captura os dados que a Monetag envia via URL
$userId = $_GET['user_id'] ?? null;
$status = $_GET['status'] ?? null; // '1' significa conversão/clique confirmado

if ($userId && $status == '1') {
    
    // 2. Chama a função RPC do Supabase para somar os pontos com segurança
    $rpcUrl = $supabaseUrl . "/rest/v1/rpc/incrementar_pontos";
    
    $data = [
        'user_id_input' => $userId,
        'quantidade' => 10 // Defina quantos pontos o usuário ganha por anúncio
    ];

    $ch = curl_init($rpcUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200 || $httpCode == 204) {
        echo "OK"; // Sucesso para a Monetag
    } else {
        error_log("Erro Supabase: " . $response);
        echo "Erro no processamento";
    }
} else {
    echo "Aguardando conversão...";
}
?>
