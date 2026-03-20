<?php
require 'config.php'; session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]); $u = $stmt->fetch();
?>
<h1>Olá, <?php echo $u['nome_pix']; ?></h1>
<h2>Saldo: <span style="color:#00dcaa;"><?php echo formatarGrana($u['saldo_pontos']); ?></span></h2>
<a href="missoes.php">Ir para Missões</a> | <a href="sacar.php">Sacar via PIX</a> | <a href="logout.php">Sair</a>
