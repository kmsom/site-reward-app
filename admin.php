
<?php
require 'config.php';
session_start();

// SEGURANÇA: Só você deve acessar. 
// Você pode melhorar isso criando uma coluna 'nivel' na tabela usuarios.
$admin_email = "seu_email_admin@gmail.com"; 

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id'] ?? 0]);
$user = $stmt->fetch();

if (!$user || $user['email'] !== $admin_email) {
    die("Acesso Restrito ao Administrador.");
}

// Lógica para Marcar como Pago
if (isset($_GET['pagar'])) {
    $id_saque = $_GET['pagar'];
    $stmt = $pdo->prepare("UPDATE saques SET status = 'pago' WHERE id = ?");
    $stmt->execute([$id_saque]);
    header("Location: admin.php?sucesso=1");
}

// Busca Saques Pendentes
$saques = $pdo->query("SELECT s.*, u.nome_pix, u.chave_pix FROM saques s 
                       JOIN usuarios u ON s.usuario_id = u.id 
                       WHERE s.status = 'pendente' ORDER BY s.data_pedido DESC")->fetchAll();

// Busca Todos os Usuários (Monitor de IP/Device)
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY data_cadastro DESC LIMIT 20")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel ADM - Mechanism Studio</title>
    <style>
        body { background: #05050a; color: #e2e8f0; font-family: sans-serif; padding: 20px; }
        .grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        .section { background: #12121e; padding: 20px; border-radius: 15px; border: 1px solid #22d3ee22; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #1f2937; }
        th { color: #22d3ee; text-transform: uppercase; font-size: 11px; }
        .btn-pago { background: #00dcaa; color: #000; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 11px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; background: #1f2937; color: #94a3af; }
    </style>
</head>
<body>

    <h2 style="color: #00dcaa;">Gestão de Pagamentos</h2>
    
    <div class="grid">
        <div class="section">
            <h3>Saques Pendentes</h3>
            <table>
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Chave PIX</th>
                        <th>Valor</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($saques as $s): ?>
                    <tr>
                        <td><strong><?php echo $s['nome_pix']; ?></strong></td>
                        <td><code><?php echo $s['chave_pix']; ?></code></td>
                        <td style="color: #00dcaa;"><?php echo formatarGrana($s['valor']); ?></td>
                        <td><a href="?pagar=<?php echo $s['id']; ?>" class="btn-pago" onclick="return confirm('Confirmar pagamento via PIX?')">CONCLUIR</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>Últimos Cadastros</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Segurança</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $u): ?>
                    <tr>
                        <td><?php echo $u['nome_pix']; ?></td>
                        <td>
                            <span class="badge">IP: <?php echo $u['ip_cadastro']; ?></span><br>
                            <span class="badge">ID: <?php echo substr($u['device_id'], 0, 15); ?>...</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
