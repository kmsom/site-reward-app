<?php
require 'config.php';
session_start();

if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$uid = $_SESSION['user_id'];
$missao_id = $_GET['m'] ?? '1';
$secret_key = "MEC_STUDIO_TOP_SECRET_99"; // Mude isso para algo difícil

// Segurança: Gera Token, Tempo e Assinatura Digital (HMAC)
$_SESSION['ad_token'] = bin2hex(random_bytes(16));
$_SESSION['ad_start_time'] = time();

// Criamos um hash que une o token, o ID do usuário e a chave secreta
$signature = hash_hmac('sha256', $_SESSION['ad_token'] . $uid . $missao_id, $secret_key);

$direct_link = "https://omg10.com/4/10753155"; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validando Missão - Mechanism</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white flex flex-col items-center justify-center min-h-screen p-6">

    <div class="text-center p-10 bg-[#16162a] rounded-[2.5rem] border border-gray-800 shadow-2xl max-w-sm w-full relative">
        <div id="loader" class="animate-spin rounded-full h-16 w-16 border-t-4 border-[#00dcaa] mx-auto mb-8"></div>
        
        <h2 id="titulo" class="text-2xl font-black mb-2 tracking-tighter">Ação Requerida</h2>
        <p id="subtitulo" class="text-gray-400 text-sm mb-8">Clique abaixo para abrir o anúncio e liberar seus <span class="text-white font-bold">0.50 Coins</span>.</p>
        
        <div id="btn_abrir">
            <a href="<?php echo $direct_link; ?>" target="_blank" onclick="iniciarContagem()" 
               class="w-full bg-white text-black font-black py-4 rounded-2xl block uppercase text-xs tracking-widest">
               Abrir Anúncio
            </a>
        </div>

        <div id="btn_receber" class="hidden">
            <a href="postback.php?m=<?php echo $missao_id; ?>&token=<?php echo $_SESSION['ad_token']; ?>&sig=<?php echo $signature; ?>" 
               class="w-full bg-[#00dcaa] text-black font-black py-4 rounded-2xl block uppercase text-xs tracking-widest shadow-lg shadow-[#00dcaa]/20">
               Coletar Recompensa
            </a>
        </div>
    </div>

    <script>
        function iniciarContagem() {
            document.getElementById('btn_abrir').classList.add('hidden');
            document.getElementById('titulo').innerText = "Aguarde...";
            document.getElementById('subtitulo').innerText = "Validando visualização em 10 segundos...";
            
            setTimeout(() => {
                document.getElementById('loader').classList.add('hidden');
                document.getElementById('titulo').innerText = "Pronto!";
                document.getElementById('subtitulo').innerText = "Clique abaixo para receber seus coins.";
                document.getElementById('btn_receber').classList.remove('hidden');
            }, 10000);
        }
    </script>
</body>
</html>
