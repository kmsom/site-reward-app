<?php
require 'config.php'; session_start();
// Mude o email abaixo para o seu
if($_SESSION['user_id'] != 1) { die("Acesso negado."); } 
$saques = $pdo->query("SELECT s.*, u.nome_pix, u.chave_pix FROM saques s JOIN usuarios u ON s.usuario_id = u.id WHERE s.status = 'pendente'")->fetchAll();
?>
<h2>Saques Pendentes</h2>
<?php foreach($saques as $s): ?>
    <p><?php echo $s['nome_pix']; ?> | <?php echo $s['chave_pix']; ?> | R$ <?php echo $s['valor']; ?> 
    <a href="admin.php?pagar=<?php echo $s['id']; ?>">Marcar Pago</a></p>
<?php endforeach; ?>
