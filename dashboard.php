<?php 
require 'config.php'; 
session_start(); 

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

// 1. BUSCA DADOS DO USUÁRIO
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$uid]); 
$u = $stmt->fetch();

// 2. CONTA QUANTAS MISSÕES ELE JÁ FEZ HOJE (Para o visual)
$stmt_contagem = $pdo->prepare("SELECT COUNT(id) as total FROM missoes_concluidas WHERE usuario_id = ? AND DATE(data_conclusao) = CURRENT_DATE");
$stmt_contagem->execute([$uid]);
$missoes_hoje = $stmt_contagem->fetch()['total'];

// 3. BUSCA O HISTÓRICO DE SAQUES (Os últimos 5)
$stmt_saques = $pdo->prepare("SELECT valor, status, data_pedido FROM saques WHERE usuario_id = ? ORDER BY data_pedido DESC LIMIT 5");
$stmt_saques->execute([$uid]);
$historico_saques = $stmt_saques->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Mechanism Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0a0a1a; color: white; }
        .glass { background: rgba(22, 22, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="pb-24">

    <div class="p-6 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-[#00dcaa] flex items-center justify-center text-[#0a0a1a] font-black uppercase">
                <?php echo substr($u['nome_pix'], 0, 1); ?>
            </div>
            <div>
                <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Membro Ativo</p>
                <h2 class="text-lg font-bold leading-tight"><?php echo explode(' ', $u['nome_pix'])[0]; ?></h2>
            </div>
        </div>
        <a href="logout.php" class="bg-red-500/10 text-red-500 px-4 py-2 rounded-xl text-xs font-bold border border-red-500/20">Sair</a>
    </div>

    <div class="px-6 mb-8">
        <div class="bg-gradient-to-br from-[#00dcaa] to-[#00b88e] p-8 rounded-[2.5rem] text-[#0a0a1a] shadow-2xl shadow-[#00dcaa]/20 relative overflow-hidden">
            <div class="relative z-10">
                <p class="font-bold opacity-70 uppercase text-[10px] tracking-widest">Saldo Disponível</p>
                <h1 class="text-4xl font-black mt-1"><?php echo formatarGrana($u['saldo_pontos']); ?></h1>
                
                <div class="mt-6 flex gap-4">
                   <div class="bg-black/10 px-3 py-1 rounded-full text-[10px] font-bold italic">
                       <?php echo $missoes_hoje; ?> MISSÕES HOJE
                   </div>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 bg-white/10 w-32 h-32 rounded-full blur-2xl"></div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 px-6 mb-8">
        <a href="missoes.php" class="glass p-6 rounded-3xl text-center hover:scale-105 transition active:scale-95">
            <div class="w-12 h-12 bg-blue-500/10 text-blue-400 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl">📺</div>
            <span class="font-bold text-sm">Missões</span>
        </a>
        <a href="sacar.php" class="glass p-6 rounded-3xl text-center hover:scale-105 transition active:scale-95">
            <div class="w-12 h-12 bg-green-500/10 text-green-400 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl">💸</div>
            <span class="font-bold text-sm">Sacar</span>
        </a>
    </div>

    <div class="mx-6 p-4 glass rounded-2xl mb-10">
        <?php 
            $meta = 10;
            $falta = $meta - $u['saldo_pontos'];
            $porcentagem = ($u['saldo_pontos'] / $meta) * 100;
            if($porcentagem > 100) $porcentagem = 100;
        ?>
        <div class="flex justify-between text-[10px] font-bold uppercase mb-2">
            <span class="text-gray-500 text-xs italic">Meta de Saque: R$ 10,00</span>
            <span class="text-[#00dcaa]"><?php echo round($porcentagem); ?>%</span>
        </div>
        <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden">
            <div class="bg-[#00dcaa] h-full transition-all duration-1000" style="width: <?php echo $porcentagem; ?>%"></div>
        </div>
        <?php if($falta > 0): ?>
            <p class="text-center text-[10px] text-gray-500 mt-3 font-medium uppercase italic">Faltam <?php echo formatarGrana($falta); ?> para liberar seu saque!</p>
        <?php else: ?>
            <p class="text-center text-[10px] text-[#00dcaa] mt-3 font-bold uppercase animate-pulse">SAQUE LIBERADO! 🚀</p>
        <?php endif; ?>
    </div>

    <div class="mx-6">
        <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4 ml-2">Histórico Recente</h3>
        
        <div class="space-y-3">
            <?php if(!$historico_saques): ?>
                <div class="p-8 text-center glass rounded-3xl">
                    <p class="text-gray-600 italic text-xs uppercase tracking-widest">Nenhuma transação encontrada</p>
                </div>
            <?php else: ?>
                <?php foreach($historico_saques as $s): ?>
                    <div class="glass p-4 rounded-2xl flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#0a0a1a] flex items-center justify-center text-xl">
                                💰
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-600 font-bold uppercase"><?php echo date('d/m/Y', strtotime($s['data_pedido'])); ?></p>
                                <p class="font-bold text-sm text-gray-200">PIX Solicitado</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-sm text-white">R$ <?php echo number_format($s['valor'], 2, ',', '.'); ?></p>
                            <?php 
                                $cor = $s['status'] == 'pago' ? 'text-[#00dcaa]' : ($s['status'] == 'pendente' ? 'text-yellow-500' : 'text-red-500');
                                $status_label = $s['status'] == 'pago' ? 'Pago' : ($s['status'] == 'pendente' ? 'Pendente' : 'Recusado');
                            ?>
                            <p class="text-[9px] font-black uppercase <?php echo $cor; ?>"><?php echo $status_label; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
