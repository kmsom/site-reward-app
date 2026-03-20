
<?php
require 'config.php';
session_start();
$uid = $_SESSION['user_id'];

// Função simples para checar se a missão foi concluída (para o visual dos botões)
function missaoCheck($pdo, $uid, $mid) {
    $stmt = $pdo->prepare("SELECT id FROM missoes_concluidas WHERE usuario_id = ? AND missao_id = ? AND DATE(data_conclusao) = CURDATE()");
    $stmt->execute([$uid, $mid]);
    return $stmt->rowCount() > 0;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Missões - Mechanism</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { background: #0a0a1a; color: white; } </style>
</head>
<body class="p-6">

    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <?php 
        // Array de missões que você criou (Exemplo de 2 missões)
        $minhas_missoes = [
            ['id' => '1', 'titulo' => 'Anúncio Prata'],
            ['id' => '2', 'titulo' => 'Anúncio Ouro']
        ];

        foreach($minhas_missoes as $missao): 
            $ja_fez = missaoCheck($pdo, $uid, $missao['id']);
        ?>
            <div id="card-<?php echo $missao['id']; ?>" class="bg-[#16162a] p-6 rounded-3xl border <?php echo $ja_fez ? 'border-gray-800 opacity-50' : 'border-[#1f2937]'; ?>">
                <h3 class="font-bold"><?php echo $missao['titulo']; ?></h3>
                <p class="text-[#00dcaa] text-2xl font-black mb-4">R$ 0,50</p>
                
                <?php if($ja_fez): ?>
                    <button disabled class="w-full bg-gray-800 text-gray-500 py-2 rounded-xl cursor-not-allowed">CONCLUÍDA</button>
                <?php else: ?>
                    <button onclick="fazerMissao('<?php echo $missao['id']; ?>')" class="w-full bg-[#00dcaa] text-black font-bold py-2 rounded-xl hover:scale-105 transition">ASSISTIR</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    </div>

    <script>
    function fazerMissao(id) {
        // 1. AVISA O BANCO E MARCA A MISSÃO
        fetch('postback.php?m=' + id)
        .then(res => res.text())
        .then(data => {
            if(data.trim() === "Sucesso") {
                // 2. DISPARA O ANÚNCIO (TAG MONETAG)
                (function(s){
                    s.dataset.zone='10753165';
                    s.src='https://al5sm.com/tag.min.js';
                })([document.documentElement, document.body].filter(Boolean).pop().appendChild(document.createElement('script')));

                // 3. RECARREGA PARA ATUALIZAR SALDO E BOTÕES
                setTimeout(() => { window.location.reload(); }, 6000);
            } else {
                alert("Você já concluiu esta missão hoje!");
            }
        });
    }
    </script>
</body>
</html>
