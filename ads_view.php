<?php
require 'config.php';
session_start();

if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Segurança: Gera um token único e salva o momento do início da missão
$_SESSION['ad_token'] = bin2hex(random_bytes(16));
$_SESSION['ad_start_time'] = time();

$missao_id = $_GET['m'] ?? 'missao_padrao';
$direct_link = "https://omg10.com/4/10753155"; // Seu link direto
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carregando Recompensa - Mechanism</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white flex flex-col items-center justify-center min-h-screen p-6">

    <div class="text-center p-10 bg-[#16162a] rounded-[2.5rem] border border-gray-800 shadow-2xl max-w-sm w-full relative overflow-hidden">
        
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-[#00dcaa] opacity-10 blur-[80px]"></div>

        <div class="mb-8">
            <div id="loader" class="animate-spin rounded-full h-16 w-16 border-t-4 border-[#00dcaa] border-opacity-20 border-t-opacity-100 mx-auto"></div>
            <div id="check-icon" class="hidden mx-auto text-[#00dcaa]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h2 id="titulo" class="text-2xl font-black mb-2">Ação Necessária</h2>
        <p id="subtitulo" class="text-gray-500 text-sm mb-8 leading-relaxed">Clique no botão abaixo para ver o anúncio e liberar seu saldo de <span class="text-white font-bold">R$ 0,50</span>.</p>
        
        <div id="area-clique-anuncio">
            <a href="<?php echo $direct_link; ?>" target="_blank" onclick="iniciarValidacao()" 
               class="w-full bg-white text-[#0a0a1a] font-black py-4 px-8 rounded-2xl transition block text-center uppercase tracking-wider">
               ABRIR ANÚNCIO
            </a>
        </div>

        <div id="area-botao" class="hidden">
            <a href="postback.php?m=<?php echo $missao_id; ?>&token=<?php echo $_SESSION['ad_token']; ?>" 
               class="w-full bg-[#00dcaa] text-[#0a0a1a] font-black py-4 px-8 rounded-2xl shadow-[0_10px_30px_rgba(0,220,170,0.3)] hover:scale-105 active:scale-95 transition block text-center uppercase tracking-wider">
               RECEBER R$ 0,50 AGORA
            </a>
        </div>
    </div>

    <script>
        function iniciarValidacao() {
            // Esconde o botão de clique e mostra o status de validação
            document.getElementById('area-clique-anuncio').classList.add('hidden');
            document.getElementById('titulo').innerText = "Validando...";
            document.getElementById('subtitulo').innerText = "Aguarde 10 segundos enquanto confirmamos sua visualização...";
            
            // Aguarda 10 segundos para liberar o botão de recebimento
            setTimeout(() => {
                document.getElementById('loader').classList.add('hidden');
                document.getElementById('check-icon').classList.remove('hidden');
                document.getElementById('titulo').innerText = "Concluído!";
                document.getElementById('subtitulo').innerHTML = "Anúncio validado. Clique abaixo para coletar seu saldo.";
                document.getElementById('area-botao').classList.remove('hidden');
            }, 10000); 
        }
    </script>

</body>
</html>
