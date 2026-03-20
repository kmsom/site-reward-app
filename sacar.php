<?php
require 'config.php'; session_start();
$uid = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT saldo_pontos FROM usuarios WHERE id = ?");
$stmt->execute([$uid]); $u = $stmt->fetch();

if(isset($_POST['sacar']) && $u['saldo_pontos'] >= 10.00) {
    $pdo->prepare("INSERT INTO saques (usuario_id, valor) VALUES (?,?)")->execute([$uid, $u['saldo_pontos']]);
    $pdo->prepare("UPDATE usuarios SET saldo_pontos = 0 WHERE id = ?")->execute([$uid]);
    echo "Saque solicitado!";
}
?>
<p>Mínimo R$ 10,00. Seu saldo: <?php echo formatarGrana($u['saldo_pontos']); ?></p>
<form method="POST"><button name="sacar">SOLICITAR PIX AGORA</button></form>
