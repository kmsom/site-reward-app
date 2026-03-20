<?php
session_start(); // 1. Sempre no topo, sem espaços antes!
require 'config.php';

// 2. Proteção: Se não tiver logado, manda pro login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

// 3. Função corrigida para PostgreSQL
function missaoCheck($pdo, $uid, $mid) {
    // No Postgres usamos CURRENT_DATE em vez de CURDATE()
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
    <title>Minhas Missões - Mechanism</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { background: #0a0a1a; color: white; } </style>
</head>
<body class="p-6">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-black mb-8 text-[#00dcaa]">MISSÕES DISPONÍVEIS</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php 
            // Adicione suas missões aqui
            $lista = [
                ['id' => '1', 'nome' => 'Anúncio Bronze'],
                ['id' => '2', 'nome' => 'Anúncio Prata'],
                ['id' => '3', 'nome' => 'Anúncio Ouro'],
            ];

            foreach($lista as $m): 
                $concluida = missaoCheck($pdo, $uid, $m['id']);
            ?>
                <div class="bg-[#16162a] p-6 rounded-3xl border <?php echo $concluida ? 'border-gray-800 opacity-50' : 'border-gray-700'; ?>">
                    <h3 class="font-bold text-lg"><?php echo $m['nome']; ?></h3>
                    <p class="text-[#00dcaa] text-2xl font-black mb-4 font-mono">R$ 0,50</p>
                    
                    <?php if($concluida): ?>
                        <button disabled class="w-full bg-gray-800 text-gray-500 py-3 rounded-xl font-bold uppercase cursor-not-allowed">Concluída</button>
                    <?php else: ?>
                        <button onclick="fazerMissao('<?php echo $m['id']; ?>')" class="w-full bg-[#00dcaa] text-black font-bold py-3 rounded-xl hover:scale-105 transition uppercase">Assistir Anúncio</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    function fazerMissao(id) {
        // Chama o postback para dar o dinheiro e registrar a conclusão
        fetch('postback.php?m=' + id)
        .then(res => res.text())
        .then(data => {
            // Se o retorno do postback for Sucesso, dispara o anúncio
            if(data.trim() === "Sucesso") {
                // SCRIPT DA MONETAG
                (function(s){
                    s.dataset.zone='10753165';
                    s.src='https://al5sm.com/tag.min.js';
                })([document.documentElement, document.body].filter(Boolean).pop().appendChild(document.createElement('script')));

                // Recarrega em 5 seg para o botão sumir
                setTimeout(() => { window.location.reload(); }, 5000);
            } else {
                alert("Erro ou Missão já concluída!");
            }
        });
    }
    </script>
</body>
</html>
