<?php 
require 'config.php'; 
session_start(); 

// SEGURANÇA: Só você (ID 1) acessa. 
// Se o seu ID no banco for diferente, mude o número abaixo.
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die("<div style='color:white; background:#0a0a1a; height:100vh; display:flex; align-items:center; justify-content:center; font-family:sans-serif;'><h1>Acesso Negado.</h1></div>");
}

// Lógica para marcar como pago
if(isset($_GET['pagar'])) {
    $id_saque = $_GET['pagar'];
    $stmt = $pdo->prepare("UPDATE saques SET status = 'pago' WHERE id = ?");
    $stmt->execute([$id_saque]);
    header("Location: admin.php?sucesso=1");
}

// Puxar saques pendentes
$saques = $pdo->query("SELECT s.*, u.nome_pix, u.chave_pix, u.ip_cadastro, u.device_id 
                       FROM saques s 
                       JOIN usuarios u ON s.usuario_id = u.id 
                       WHERE s.status = 'pendente' 
                       ORDER BY s.data_pedido DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Mechanism Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white p-6">

    <div class="max-w-6xl mx-auto">
        <header class="flex justify-between items-center mb-10">
            <h1 class="text-2xl font-bold text-[#00dcaa]">Painel do <span class="text-white">Administrador</span></h1>
            <a href="dashboard.php" class="text-sm text-gray-400 hover:text-white">Voltar ao Site</a>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-[#16162a] p-6 rounded-2xl border border-gray-800">
                <p class="text-gray-400 text-sm">Saques Pendentes</p>
                <h2 class="text-3xl font-black"><?php echo count($saques); ?></h2>
            </div>
            <div class="bg-[#16162a] p-6 rounded-2xl border border-gray-800">
                <p class="text-gray-400 text-sm">Status do Sistema</p>
                <h2 class="text-3xl font-black text-green-500">Online</h2>
            </div>
        </div>

        <h3 class="text-xl font-bold mb-6">Solicitações de Saque</h3>

        <?php if(count($saques) == 0): ?>
            <div class="bg-[#16162a] p-10 rounded-2xl text-center border border-dashed border-gray-800 text-gray-500">
                Nenhum saque pendente no momento.
            </div>
        <?php endif; ?>

        <div class="space-y-4">
            <?php foreach($saques as $s): ?>
                <div class="bg-[#16162a] border border-gray-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex-1">
                        <h4 class="font-bold text-lg text-white"><?php echo $s['nome_pix']; ?></h4>
                        <p class="text-[#00dcaa] font-mono text-sm"><?php echo $s['chave_pix']; ?></p>
                        <div class="mt-2 text-xs text-gray-500 space-y-1">
                            <p><b>IP:</b> <?php echo $s['ip_cadastro']; ?></p>
                            <p class="truncate max-w-xs"><b>Device:</b> <?php echo substr($s['device_id'], 0, 50); ?>...</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-2xl font-black text-white mb-2">R$ <?php echo number_format($s['valor'], 2, ',', '.'); ?></p>
                        <a href="admin.php?pagar=<?php echo $s['id']; ?>" 
                           onclick="return confirm('Confirmar pagamento via PIX?')"
                           class="bg-[#00dcaa] text-[#0a0a1a] px-6 py-2 rounded-xl font-bold text-sm hover:bg-white transition">
                            MARCAR COMO PAGO
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
