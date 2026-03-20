<?php 
require 'config.php'; 
session_start(); 

if(!isset($_SESSION['user_id'])) header("Location: login.php");
$uid = $_SESSION['user_id'];

// Função para checar se a missão foi feita nas últimas 24h
function jaFez($pdo, $uid, $mid) {
    $stmt = $pdo->prepare("SELECT id FROM missoes_concluidas 
                           WHERE usuario_id = ? AND missao_id = ? 
                           AND data_conclusao > NOW() - INTERVAL '24 hours'");
    $stmt->execute([$uid, $mid]);
    return $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missões Diárias - Mechanism Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white min-h-screen pb-10">

    <div class="p-6 flex items-center gap-4">
        <a href="dashboard.php" class="text-gray-400 text-2xl">←</a>
        <h1 class="text-xl font-bold text-[#00dcaa]">Missões Diárias</h1>
    </div>

    <div class="px-6 space-y-4">
        <p class="text-gray-400 text-sm mb-6">Assista aos anúncios completos para liberar sua recompensa de <b>R$ 0,50</b>.</p>

        <?php 
        // Gerando 5 missões de exemplo
        for($i=1; $i<=5; $i++): 
            $missao_id = "missao_0$i";
            $concluida = jaFez($pdo, $uid, $missao_id);
        ?>
            
            <div class="bg-[#16162a] border <?php echo $concluida ? 'border-gray-800 opacity-60' : 'border-[#00dcaa]/30'; ?> p-5 rounded-2xl flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">Vídeo Recompensado #<?php echo $i; ?></h3>
                    <p class="text-[#00dcaa] font-bold text-sm">+ R$ 0,50</p>
                </div>

                <?php if(!$concluida): ?>
                    <a href="ads_view.php?m=<?php echo $missao_id; ?>" 
                       class="bg-[#00dcaa] text-[#0a0a1a] px-5 py-2 rounded-xl font-black text-xs uppercase tracking-wider hover:scale-105 transition">
                        ASSISTIR
                    </a>
                <?php else: ?>
                    <div class="bg-gray-800 text-gray-500 px-4 py-2 rounded-xl font-bold text-xs uppercase italic">
                        CONCLUÍDO
                    </div>
                <?php endif; ?>
            </div>

        <?php endfor; ?>
    </div>

    <div class="mt-10 mx-6 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-xl text-yellow-500 text-xs text-center">
        As missões resetam individualmente 24 horas após a conclusão.
    </div>

</body>
</html>
