<?php
require 'config.php';
session_start();

// Pega o ID da missão para saber para onde voltar depois
$mid = $_GET['m'] ?? 'missao_01';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carregando Recompensa...</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white flex flex-col items-center justify-center min-h-screen p-6">

    <div class="text-center p-10 bg-[#16162a] rounded-3xl border border-gray-800 shadow-2xl max-w-sm w-full">
        <div class="mb-6">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#00dcaa] mx-auto"></div>
        </div>

        <h2 class="text-xl font-bold mb-2">Validando Visualização</h2>
        <p class="text-gray-500 text-sm mb-6">Aguarde o anúncio carregar para liberar seu saldo.</p>
        
        <div id="status" class="text-[#00dcaa] font-mono text-sm">Carregando script...</div>

        <div id="btn-container" class="hidden mt-6">
            <a href="postback.php?m=<?php echo $mid; ?>" class="bg-[#00dcaa] text-black font-bold py-3 px-6 rounded-xl animate-bounce inline-block">
                RECEBER R$ 0,50
            </a>
        </div>
    </div>

    <script>
        (function(s){
            s.dataset.zone='10753165';
            s.src='https://al5sm.com/tag.min.js';
        })([document.documentElement, document.body].filter(Boolean).pop().appendChild(document.createElement('script')));
    </script>

    <script>
        // Lógica para liberar o botão após 5 segundos (tempo para o anúncio carregar/exibir)
        setTimeout(() => {
            document.getElementById('status').innerText = "Pronto! Clique abaixo:";
            document.getElementById('btn-container').classList.remove('hidden');
        }, 5000); 
    </script>

</body>
</html>
