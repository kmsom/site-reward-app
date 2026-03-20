<?php $id_do_usuario_logado = "USUARIO_TESTE_01"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vídeo Recompensado</title>
    <style>
        body { font-family: sans-serif; background: #0f172a; color: white; text-align: center; padding: 20px; }
        .video-box { background: #1e293b; border: 2px solid #334155; border-radius: 20px; padding: 40px 20px; max-width: 400px; margin: 30px auto; }
        .timer-circle { width: 80px; height: 80px; border: 4px solid #3b82f6; border-radius: 50%; line-height: 80px; font-size: 24px; margin: 0 auto 20px; font-weight: bold; color: #3b82f6; }
        .btn-assistir { background: #3b82f6; color: white; border: none; padding: 15px 30px; border-radius: 50px; font-size: 18px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-resgatar { background: #10b981; color: white; border: none; padding: 15px 30px; border-radius: 50px; font-size: 18px; font-weight: bold; cursor: pointer; display: none; width: 100%; }
        .status-msg { margin-top: 15px; color: #94a3b8; font-size: 14px; }
    </style>
</head>
<body>

    <div class="video-box">
        <div id="timerContainer" style="display: none;">
            <div class="timer-circle" id="timerNum">15</div>
            <p>Assistindo anúncio... Não feche esta página!</p>
        </div>

        <div id="startContainer">
            <h2>Ganhe 1 Pontos</h2>
            <p>Assista ao vídeo completo para receber sua recompensa.</p>
            <a href="https://inklinkor.com/4/10753155" target="_blank" class="btn-assistir" onclick="iniciarVideo()">
                ▶ ASSISTIR VÍDEO
            </a>
        </div>

        <button id="btnResgatar" class="btn-resgatar" onclick="liberarPontos()">
            🎁 RESGATAR MEUS PONTOS
        </button>
        
        <p class="status-msg" id="statusMsg">Anúncios por Monetag</p>
    </div>

    <script>
        let tempo = 15;
        let cronometro;

        function iniciarVideo() {
            // Esconde o botão de início e mostra o timer
            document.getElementById('startContainer').style.display = 'none';
            document.getElementById('timerContainer').style.display = 'block';

            // Inicia a contagem regressiva
            cronometro = setInterval(() => {
                tempo--;
                document.getElementById('timerNum').innerText = tempo;

                if (tempo <= 0) {
                    clearInterval(cronometro);
                    document.getElementById('timerContainer').style.display = 'none';
                    document.getElementById('btnResgatar').style.display = 'block';
                    document.getElementById('statusMsg').innerText = "Vídeo finalizado! Resgate agora.";
                }
            }, 1000);
        }

        function liberarPontos() {
            const btn = document.getElementById('btnResgatar');
            btn.innerText = "Processando...";
            btn.disabled = true;

            // Chama o seu postback.php via fetch (a "gambiarra" que substitui o S2S)
            fetch(`postback.php?user_id=<?php echo $id_do_usuario_logado; ?>&status=1`)
            .then(res => res.text())
            .then(data => {
                if(data.trim() === "OK") {
                    alert("Sucesso! 10 pontos adicionados.");
                    location.reload();
                } else {
                    alert("Erro no servidor. Tente novamente.");
                    btn.disabled = false;
                    btn.innerText = "RESGATAR MEUS PONTOS";
                }
            });
        }
    </script>

</body>
</html>
