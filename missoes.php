<?php 
session_start(); 
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

function missaoCheck($pdo, $uid, $mid) {
    $sql = "SELECT id FROM missoes_concluidas WHERE usuario_id = ? AND missao_id = ? AND DATE(data_conclusao) = CURRENT_DATE";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uid, $mid]);
    return $stmt->rowCount() > 0;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Missões - Mechanism</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { background: #0a0a1a; color: white; } .glass { background: rgba(22, 22, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.05); } </style>
</head>
<body class="p-6 pb-24">

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-black text-[#00dcaa] uppercase tracking-tighter">Missões Disponíveis</h1>
            <a href="dashboard.php" class="text-xs font-bold text-gray-500 hover:text-white transition">← VOLTAR</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php 
            $lista = [
                ['id' => '1', 'nome' => 'Anúncio Bronze', 'cor' => 'blue-500'],
                ['id' => '2', 'nome' => 'Anúncio Prata', 'cor' => 'gray-400'],
                ['id' => '3', 'nome' => 'Anúncio Ouro', 'cor' => 'yellow-500'],
            ];

            foreach($lista as $m): 
                $concluida = missaoCheck($pdo, $uid, $m['id']);
            ?>
                <div class="glass p-6 rounded-[2rem] relative overflow-hidden <?php echo $concluida ? 'opacity-40' : ''; ?>">
                    <div class="relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-widest text-<?php echo $m['cor']; ?>"><?php echo $m['nome']; ?></span>
                        <p class="text-2xl font-black mt-1 mb-6">0.20 <span class="text-xs opacity-50">COINS</span></p>
                        
                        <?php if($concluida): ?>
                            <button disabled class="w-full bg-gray-800/50 text-gray-500 py-4 rounded-2xl font-black uppercase text-xs cursor-not-allowed">Concluída Hoje</button>
                        <?php else: ?>
                            <a href="ads_view.php?m=<?php echo $m['id']; ?>" class="block text-center w-full bg-[#00dcaa] text-[#0a0a1a] font-black py-4 rounded-2xl hover:scale-[1.02] transition active:scale-95 uppercase text-xs shadow-lg shadow-[#00dcaa]/10">
                                Assistir Anúncio
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
