<?php 
require 'config.php'; 
session_start(); 

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

// 1. BUSCA O SALDO_BRL (Dinheiro real convertido)
$stmt = $pdo->prepare("SELECT saldo_brl, chave_pix FROM usuarios WHERE id = ?");
$stmt->execute([$uid]); 
$u = $stmt->fetch();

$mensagem = "";
$minimo_saque = 1.00; // Novo mínimo de R$ 1,00

// 2. Lógica de Saque Baseada no saldo_brl
if(isset($_POST['sacar']) && $u['saldo_brl'] >= $minimo_saque) {
    $valor_saque = $u['saldo_brl'];
    
    try {
        $pdo->beginTransaction();

        // Insere o pedido na tabela de saques
        $stmt_saque = $pdo->prepare("INSERT INTO saques (usuario_id, valor, status, data_pedido) VALUES (?, ?, 'pendente', NOW())");
        $stmt_saque->execute([$uid, $valor_saque]);
        
        // Zera o saldo_brl do usuário
        $stmt_user = $pdo->prepare("UPDATE usuarios SET saldo_brl = 0 WHERE id = ?");
        $stmt_user->execute([$uid]);
        
        $pdo->commit();

        // Atualiza a variável local para o visual mudar na hora
        $u['saldo_brl'] = 0;
        $mensagem = "Saque de R$ " . number_format($valor_saque, 2, ',', '.') . " solicitado com sucesso!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $mensagem = "Erro ao processar saque. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacar PIX - Mechanism Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-[#16162a] border border-gray-800 rounded-3xl p-8 shadow-2xl">
        
        <div class="flex justify-between items-center mb-8">
            <a href="dashboard.php" class="text-gray-500 hover:text-white transition text-sm">← Voltar</a>
            <span class="text-[#00dcaa] font-bold text-xs uppercase tracking-widest">Resgate</span>
        </div>

        <h1 class="text-2xl font-black mb-2">Solicitar Saque</h1>
        <p class="text-gray-400 text-sm mb-8">O valor será enviado para sua chave PIX cadastrada.</p>

        <?php if($mensagem != ""): ?>
            <div class="bg-green-500/10 border border-green-500/50 text-green-500 p-4 rounded-xl mb-6 text-sm text-center font-medium">
                🎉 <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <div class="bg-[#0a0a1a] border border-gray-800 p-6 rounded-2xl mb-8 text-center">
            <p class="text-gray-500 text-xs uppercase font-bold mb-1">Saldo para Saque (BRL)</p>
            <h2 class="text-4xl font-black text-[#00dcaa]">
                <?php echo formatarGrana($u['saldo_brl']); ?>
            </h2>
            <div class="mt-4 pt-4 border-t border-gray-800/50">
                <p class="text-gray-500 text-[10px] uppercase mb-1">Sua Chave PIX</p>
                <p class="text-white font-mono text-sm truncate"><?php echo $u['chave_pix'] ?? 'Não cadastrada'; ?></p>
            </div>
        </div>

        <form method="POST">
            <?php if($u['saldo_brl'] >= $minimo_saque): ?>
                <button name="sacar" class="w-full bg-[#00dcaa] text-[#0a0a1a] font-bold py-4 rounded-2xl hover:bg-[#00b88e] transition transform active:scale-95 shadow-[0_10px_20px_rgba(0,220,170,0.2)]">
                    SOLICITAR PIX AGORA
                </button>
                <p class="text-center text-[10px] text-gray-500 mt-4">Processamento em até 24 horas úteis.</p>
            <?php else: ?>
                <button disabled class="w-full bg-gray-800 text-gray-500 font-bold py-4 rounded-2xl cursor-not-allowed opacity-50">
                    MÍNIMO R$ 1,00
                </button>
                <div class="mt-4 bg-yellow-500/5 border border-yellow-500/20 p-3 rounded-xl">
                    <p class="text-center text-[11px] text-yellow-500/80">
                        Você precisa de mais <b><?php echo formatarGrana($minimo_saque - $u['saldo_brl']); ?></b> para realizar o resgate.
                    </p>
                </div>
            <?php endif; ?>
        </form>

    </div>

</body>
</html>
