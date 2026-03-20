<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit; }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="UTF-8"><title>Mechanism Studio - Ganhe PIX</title></head>
<body style="background:#0a0a1a; color:white; text-align:center; font-family:sans-serif; padding-top:100px;">
    <h1>💰 Ganhe assistindo anúncios</h1>
    <p>Renda extra diária direto no seu PIX.</p><br>
    <a href="cadastro.php" style="background:#00dcaa; padding:15px 30px; color:#000; text-decoration:none; font-weight:bold; border-radius:30px;">COMEÇAR AGORA</a><br><br>
    <a href="login.php" style="color:#22d3ee; text-decoration:none;">Já tenho conta (Entrar)</a>
</body>
</html>
