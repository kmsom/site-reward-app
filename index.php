<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <title>Missão - Site Reward</title>
    <style>
        /* Visual Dark do Print */
        body { background: #0a0a1a; color: white; font-family: sans-serif; margin: 0; padding: 20px; text-align: center; }
        .timer-badge { 
            background: rgba(0,0,0,0.6); color: #22d3ee; padding: 8px 20px; 
            border-radius: 50px; font-weight: bold; border: 1px solid #22d3ee44; 
            display: inline-block; margin-top: 15px; 
        }
        .card { 
            background: #12121e; border-radius: 24px; padding: 25px; 
            margin: 30px auto; max-width: 380px; border: 1px solid rgba(139, 92, 246, 0.15); 
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.05);
        }
        .btn-ready { 
            background: linear-gradient(90deg, #00dcaa, #22d3ee); color: #0a0a1a; 
            padding: 18px; width: 100%; border-radius: 16px; border: none; 
            font-size: 18px; font-weight: bold; cursor: pointer; display: none;
        }
        .btn-locked { 
            background: #1e1e2e; color: #4b5563; padding: 18px; width: 100%; 
            border-radius: 16px; border: none; font-size: 18px; font-weight: bold; 
        }
    </style>
</head>
<body>

    <div class="timer-badge" id="timerDisplay">AGUARDANDO CLIQUE...</div>

    <div class="card" id="adContainer">
        <h3 style="margin-top: 0;">Tarefa Premiada</h3>
        <p style="color: #94a3af; font-size: 14px;">Clique no botão "ABRIR ANÚNCIO" abaixo. O timer de 20s começará assim que a nova aba abrir.</p>
        
        <button class="btn-ready" id="btnFake" style="display: block; margin-top: 20px;">ABRIR ANÚNCIO</button>
    </div>

    <button id="btnFinal" class="btn-locked">AGUARDE...</button>

    <script>(function(s,u,z,p){s.src=u;s.setAttribute('data-zone',z);p.appendChild(s);})(document.createElement('script'),'https://inklinkor.com/tag.min.js',10753165,document.body||document.documentElement);</script>

    <script>
        const tg = window.Telegram.WebApp;
        tg.expand();

        let count = 20;
        let running = false;
        const btnFake = document.getElementById('btnFake');
        const btnFinal = document.getElementById('btnFinal');
        const timerDisplay = document.getElementById('timerDisplay');

        // Quando o usuário clica no botão, o anúncio abre em outra aba e o timer começa
        btnFake.onclick = () => {
            if (!running) {
                running = true;
                btnFake.style.display = 'none'; // Esconde o botão de clique
                startCountdown();
            }
        };

        function startCountdown() {
            const interval = setInterval(() => {
                count--;
                timerDisplay.innerText = `RECARREGANDO EM ${count}s`;
                
                if (count <= 0) {
                    clearInterval(interval);
                    timerDisplay.style.display = 'none';
                    btnFinal.innerText = "CONTINUAR";
                    btnFinal.className = "btn-ready";
                    btnFinal.style.display = "block";
                    btnFinal.onclick = () => {
                        window.location.href = `postback.php?user_id=${tg.initDataUnsafe?.user?.id || 'teste'}&status=1`;
                    };
                }
            }, 1000);
        }
    </script>
</body>
</html>
