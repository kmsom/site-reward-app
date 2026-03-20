<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$uid = $_SESSION['user_id'];
$msg_erro = "";
$msg_sucesso = "";

// Buscar saldo atual do banco
$stmt = $pdo->prepare("SELECT saldo_pontos, chave_pix FROM usuarios WHERE id = ?");
$stmt->execute([$uid]);
$user = $stmt->fetch();

$minimo_saque = 20.00; // Você define o valor mínimo aqui

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valor_saque = floatval($_POST['valor']);

    if ($valor_saque < $minimo_saque) {
        $msg_erro = "O valor mínimo para saque é " . formatarGrana($minimo_saque);
    } elseif ($valor_saque > $user['saldo_pontos']) {
        $msg_erro = "Saldo insuficiente!";
    } else {
        try {
            $pdo->beginTransaction();

            // 1. Subtrai do saldo do usuário
            $sql1 = "UPDATE usuarios SET saldo_pontos = saldo_pontos - ? WHERE id = ?";
            $pdo->prepare($sql1)->execute([$valor_saque, $uid]);

            // 2. Registra o pedido de saque
            $sql2 = "INSERT INTO saques (usuario_id, valor, status) VALUES (?, ?, 'pendente')";
            $pdo->prepare($sql2)->execute([$uid, $valor_saque]);

            $pdo->commit();
            $msg_sucesso = "Solicitação enviada! Aguarde o pagamento via PIX.";
            
            // Atualiza o saldo na tela
            $user['saldo_pontos'] -= $valor_saque;
        } catch (Exception $e) {
            $pdo->rollBack();
            $msg_erro = "Erro ao processar. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Saque - PIX</title>
    <style>
        body { background: #0a0a1a; color: white; font-family: sans-serif; padding: 20px; text-align: center; }
        .card { background: #12121e; padding: 30px; border-radius: 24px; border: 1px solid #22d3ee22; max-width: 400px; margin: auto; }
        .saldo-box { background: rgba(0, 220, 170, 0.1); padding: 15px; border-radius: 15px; border: 1px solid #00dcaa44; margin-bottom: 20px; }
        input { width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #1f2937; background: #0a0a1a; color: white; margin: 10px 0; box-sizing: border-box; font-size: 18px; text-align: center; }
        .btn-sacar { background: linear-gradient(90deg, #00dcaa, #22d3ee); color: #0a0a1a; padding: 18px; width: 100%; border-radius: 12px; border: none; font-weight: bold; cursor: pointer; }
        .msg { font-size: 14px; margin-bottom: 15px; padding: 10px; border-radius: 8px; }
    </style>
</head>
<body>

    <a href="dashboard.php" style="color: #64748b; text-decoration: none; display: block; margin-bottom: 20px;">← Voltar ao Painel</a>

    <div class="card">
        <h2 style="color: #22d3ee; margin-top: 0;">Resgate via PIX</h2>
        
        <div class="saldo-box">
            <small>Seu Saldo Atual</small><br>
            <strong style="font-size: 24px; color: #00dcaa;"><?php echo formatarGrana($user['saldo_pontos']); ?></strong>
        </div>

        <?php if($msg_erro) echo "<div class='msg' style='background:#ff444422; color:#ff4444;'>$msg_erro</div>"; ?>
        <?php if($msg_sucesso) echo "<div class='msg' style='background:#00dcaa22; color:#00dcaa;'>$msg_sucesso</div>"; ?>

        <form method="POST">
            <label style="font-size: 12px; color: #64748b;">Digite o valor que deseja sacar:</label>
            <input type="number" step="0.01" name="valor" placeholder="0,00" required>
            <p style="font-size: 11px; color: #4b5563;">Chave PIX: <?php echo $user['chave_pix']; ?></p>
            <button type="submit" class="btn-sacar">SOLICITAR PAGAMENTO</button>
        </form>
    </div>

</body>
</html>
