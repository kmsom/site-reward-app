<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        body { background: #0a0a1a; color: white; font-family: sans-serif; text-align: center; padding: 20px; }
        .timer { background: #12121e; color: #22d3ee; padding: 10px 20px; border-radius: 50px; font-weight: bold; display: inline-block; border: 1px solid #22d3ee44; margin-top: 20px; }
        .card { background: #12121e; border-radius: 20px; padding: 30px; margin-top: 30px; border: 1px solid #ffffff11; }
        .btn-ready { background: linear-gradient(90deg, #00dcaa, #22d3ee); color: #000; padding: 18px; width: 100%; border-radius: 12px; font-weight: bold; border: none; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="timer" id="status">AGUARDANDO ANÚNCIO...</div>

    <div class="card">
        <h3>Missão Premiada</h3>
        <p style="color: #888;">O contador de 20s começará quando o anúncio carregar.</p>
        
        <script>(function(s,u,z,p){s.src=u;s.setAttribute('data-zone',z);p.appendChild(s);})(document.createElement('script'),'https://inklinkor.com/tag.min.js',10753167,document.body||document.documentElement);</script>
    </div>

    <button id="btnFinal" style="display:none;" class="btn-ready">RESGATAR RECOMPENSA</button>

    <script>
        const tg = window.Telegram.WebApp;
        tg.expand();

        let count = 20;
        let running = false;

        // LÓGICA DE DETECÇÃO (O segredo do print)
        const monitor = setInterval(() => {
            // Se achar o iframe ou o z-index da Monetag, solta o timer
            const ad = document.querySelector('iframe, [class*="monetag"], div[style*="z-index: 2147483647"]');
            if (ad && !running) {
                running = true;
                clearInterval(monitor);
                startTimer();
            }
        }, 1000);

        function startTimer() {
            const timerEl = document.getElementById('status');
            const loop = setInterval(() => {
                count--;
                timerEl.innerText = `RECARREGANDO EM ${count}s`;
                if (count <= 0) {
                    clearInterval(loop);
                    timerEl.style.display = 'none';
                    document.getElementById('btnFinal').style.display = 'block';
                }
            }, 1000);
        }

        document.getElementById('btnFinal').onclick = () => {
            window.location.href = `postback.php?user_id=${tg.initDataUnsafe?.user?.id || 'teste'}&status=1`;
        };
    </script>
</body>
</html>
