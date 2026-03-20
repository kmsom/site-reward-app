<?php
$host = 'aws-0-us-west-2.pooler.supabase.com'; 
$port = '5432';
$db   = 'postgres';
$user = 'postgres.zbeddlxzqqivtjmugaze';
$pass = 'mecanismo2026@'; 

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db;sslmode=require", $user, $pass);
    
    // Usando números (3 e 2) para o PHP não travar se o driver estiver instável
    $pdo->setAttribute(3, 2); 
    
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

function formatarGrana($valor) {
    return "R$ " . number_format($valor, 2, ',', '.');
}
?>
