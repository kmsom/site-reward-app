<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        body { background: #0a0a1a; color: white; font-family: sans-serif; margin: 0; padding: 20px; text-align: center; }
        .timer-badge { 
            background: rgba(0,0,0,0.6); color: #22d3ee; padding: 10px 25px; 
            border-radius: 50px; font-weight: bold; border: 1px solid #22d3ee44; 
            display: inline-block; margin-top: 15px; font-size: 1.2rem;
        }
        .card { 
            background: #12121e; border-radius: 24px; padding: 25px; 
            margin: 30px auto; max-width: 380px; border: 1px solid rgba(139, 92, 246, 0.2); 
        }
        .btn-action { 
            background: linear-gradient(90deg, #00dcaa, #22d3ee); color: #0a0a1a; 
            padding: 18px; width: 100%; border-radius: 16px; border: none; 
            font-size: 18px; font-weight: bold; cursor: pointer;
        }
        .btn-locked { 
            background: #1e1e2e; color: #4b5563; padding: 18px; width: 100%; 
            border-radius: 16px; border: none; font-size: 18px; font-weight: bold; cursor: not-allowed;
        }
        #btnFinal { display: none; }
    </style>
</head>
<body>

    <div class="timer-badge" id="timerDisplay">AGUARDANDO ANÚNCIO_2026...</div>

    <div class="card" id="mainCard">
        <h3 style="color: #22d3ee;">Tarefa Premiada</h3>
        <p style="color: #94a3af; font-size: 14px;">
            1. Clique no botão abaixo para abrir o anúncio.<br>
            2. O timer de 20s iniciará automaticamente.<br>
            3. Não feche esta página.
        </p>
        
        <button class="btn-action" id="triggerAd">CLIQUE PARA ASSISTIR</button>
    </div>

    <button id="btnFinal" class="btn-action">RESGATAR AGORA</button>
    <button id="btnLocked" class="btn-locked">AGUARDE O TIMER...</button>

    <script>(function(s,u,z,p){s.src=u;s.setAttribute('data-zone',z);p.appendChild(s);})(document.createElement('script'),'https://inklinkor.com/tag.min.js',10753165,document.body||document.documentElement);</script>

    <script>
        const tg = window.Telegram.WebApp;
        tg.expand();

        let count = 20;
        let running = false;
        const triggerBtn = document.getElementById('triggerAd');
        const timerDisplay = document.getElementById('timerDisplay');

        // FUNÇÃO QUE INICIA A CONTAGEM
        function startTimer() {
            if (running) return;
            running = true;
            
            triggerBtn.innerText = "ANÚNCIO ABERTO!";
            triggerBtn.disabled = true;
            triggerBtn.style.opacity = "0.5";

            const countdown = setInterval(() => {
                count--;
                timerDisplay.innerText = `RECARREGANDO EM ${count}s`;
                
                if (count <= 0) {
                    clearInterval(countdown);
                    timerDisplay.style.display = 'none';
                    document.getElementById('mainCard').style.display = 'none';
                    document.getElementById('btnLocked').style.display = 'none';
                    
                    const btnFinal = document.getElementById('btnFinal');
                    btnFinal.style.display = 'block';
                    btnFinal.onclick = () => {
                        window.location.href = `postback.php?user_id=${tg.initDataUnsafe?.user?.id || 'teste'}&status=1`;
                    };
                }
            }, 1000);
        }

        // GATILHO: Inicia ao clicar no botão (que abre o anúncio em outra aba)
        triggerBtn.addEventListener('click', function() {
            setTimeout(startTimer, 500); // Pequeno atraso para garantir que o OnClick da Monetag dispare primeiro
        });

        // GATILHO EXTRA: Se o usuário mudar de aba e voltar (garante que o timer rode)
        window.addEventListener('blur', function() {
            if (!running) startTimer();
        });
    </script>
</body>
</html>
