<?php
$host = 'db.zbeddlxzqqivtjmugaze.supabase.co'; // Host do seu print
$port = '5432';
$db   = 'postgres';
$user = 'postgres';
$pass = 'mecanismo2026@'; 

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

function formatarGrana($valor) {
    return "R$ " . number_format($valor, 2, ',', '.');
}
?>
