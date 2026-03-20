<?php
require 'config.php'; session_start();
$uid = $_SESSION['user_id'];
function jaFez($pdo, $uid, $mid) {
    $s = $pdo->prepare("SELECT id FROM missoes_concluidas WHERE usuario_id = ? AND missao_id = ? AND data_conclusao > NOW() - INTERVAL '24 hours'");
    $s->execute([$uid, $mid]); return $s->fetch();
}
?>
<h3>Missões Disponíveis</h3>
<?php for($i=1; $i<=5; $i++): ?>
    <?php if(!jaFez($pdo, $uid, "m$i")): ?>
        <a href="ads_view.php?m=m<?php echo $i; ?>">Missão <?php echo $i; ?> (R$ 0,50)</a><br>
    <?php else: ?>
        <button disabled>Aguarde 24h</button><br>
    <?php endif; ?>
<?php endfor; ?>
