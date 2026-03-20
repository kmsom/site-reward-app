<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome']; $email = $_POST['email']; $pix = $_POST['pix'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $ip = $_SERVER['REMOTE_ADDR'];
    $device = $_SERVER['HTTP_USER_AGENT'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome_pix, email, senha, chave_pix, ip_cadastro, device_id) VALUES (?,?,?,?,?,?)");
    if($stmt->execute([$nome, $email, $senha, $pix, $ip, $device])) {
        header("Location: login.php?sucesso=1");
    }
}
?>
<form method="POST"><input name="nome" placeholder="Nome do PIX" required><input name="email" type="email" placeholder="E-mail" required><input name="pix" placeholder="Chave PIX" required><input name="senha" type="password" placeholder="Senha" required><button type="submit">CRIAR CONTA</button></form>
