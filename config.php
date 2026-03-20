<?php
// Dados atualizados do seu novo print (Session Pooler)
$host = 'aws-0-us-west-2.pooler.supabase.com'; 
$port = '5432'; // Pode manter 5432 aqui pois o host do pooler já resolve o IPv4
$db   = 'postgres';

// ATENÇÃO: O usuário agora é esse nome longo com o ID do projeto
$user = 'postgres.zbeddlxzqqivtjmugaze';

// A senha que você resetou lá no Supabase
$pass = 'mecanismo2026@'; 

try {
    // Adicionamos sslmode=require para garantir a segurança da conexão
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db;sslmode=require", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
    
    // Se quiser testar, pode descomentar a linha abaixo:
    // echo "Conexão estabelecida com o Pooler!";
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

function formatarGrana($valor) {
    return "R$ " . number_format($valor, 2, ',', '.');
}
?>
