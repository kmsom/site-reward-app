<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanism Studio - Ganhe com Anúncios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white font-sans">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 text-center">
        <div class="mb-8 animate-bounce">
            <span class="bg-[#00dcaa] text-[#0a0a1a] px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest">Lançamento 2026</span>
        </div>
        
        <h1 class="text-5xl md:text-6xl font-black mb-4 leading-tight">
            Sua Renda Extra <br> <span class="text-[#00dcaa]">Direto no PIX.</span>
        </h1>
        
        <p class="text-gray-400 max-w-md text-lg mb-10">
            Assista anúncios curtos, acumule pontos e saque dinheiro real todos os dias. Simples, rápido e seguro.
        </p>

        <div class="flex flex-col w-full max-w-xs gap-4">
            <a href="cadastro.php" class="bg-[#00dcaa] text-[#0a0a1a] font-bold py-4 rounded-2xl hover:bg-[#00b88e] transition shadow-[0_0_20px_rgba(0,220,170,0.3)]">
                COMEÇAR AGORA
            </a>
            <a href="login.php" class="border border-gray-700 py-4 rounded-2xl hover:bg-gray-800 transition">
                Já tenho uma conta
            </a>
        </div>

        <div class="mt-16 flex gap-8 text-gray-500 text-sm">
            <div><b class="text-white block text-xl">R$ 0,50</b> por anúncio</div>
            <div><b class="text-white block text-xl">R$ 10,00</b> saque mínimo</div>
        </div>
    </div>

    <div style="display:none !important;">
        <div id="frame" style="width: 1px; height: 1px; opacity: 0;">
            <iframe data-aa='2432077' src='//acceptable.a-ads.com/2432077/?size=Adaptive'
                    style='border:0; padding:0; width:100%; height:100%; overflow:hidden;'></iframe>
        </div>
    </div>
    </body>
</html>
