<?php
require 'config.php'; session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$_POST['email']]); $user = $stmt->fetch();
    if ($user && password_verify($_POST['senha'], $user['senha'])) {
        $_SESSION['user_id'] = $user['id']; header("Location: dashboard.php");
    }
}
?>
<form method="POST"><input name="email" type="email" placeholder="E-mail"><input name="senha" type="password" placeholder="Senha"><button type="submit">ENTRAR</button></form>
