<?php $id_do_usuario_logado = "USUARIO_TESTE_01"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward App</title>
    <style>
        body { 
            font-family: sans-serif; 
            background-color: #1a222d; /* Cor escura do print */
            background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png'); /* Padronagem de fundo */
            color: white; 
            text-align: center; 
            margin: 0; 
            padding: 20px; 
        }
        
        .timer-badge {
            background: rgba(0, 0, 0, 0.6);
            padding: 8px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
            border: 1px solid #334155;
        }

        .ad-card {
            background: #1e293b;
            border-radius: 15px;
            margin: 20px auto;
            max-width: 350px;
            overflow: hidden;
            border: 1px solid #334155;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }

        .ad-image { width: 100%; display: block; }
        
        .ad-content { padding: 15px; text-align: left; }
        .ad-title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .ad-desc { font-size: 14px; color: #94a3b8; margin-bottom: 15px; }

        .btn-sim {
            background: #2563eb;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
        }

        .footer-text { font-size: 12px; color: #64748b; margin: 15px 0; }

        .reward-bar {
            background: #166534;
            color: #4ade80;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-continuar {
            background: #1e3a8a;
            color: #94a3b8;
            padding: 18px;
            width: 100%;
            max-width: 350px;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: bold;
            cursor: not-allowed;
            transition: 0.3s;
        }

        .btn-ready {
            background: #2563eb;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="timer-badge" id="timer">00:15</div>

    <div class="ad-card">
        <img src="https://i.imgur.com/8QvR6fX.png" class="ad-image" alt="Ad">
        <div class="ad-content">
            <div class="ad-title">Arara777</div>
            <div class="ad-desc">👉 Nova plataforma! Clique em SIM para receber seu bônus de $25!</div>
            <button class="btn-sim" onclick="location.reload()">SIM</button>
        </div>
    </div>

    <div class="footer-text">ads by Monetag</div>

    <div class="reward-bar">
        <span>▼ Clique para receber a recompensa!</span>
    </div>

    <button id="btnFinal" class="btn-continuar" disabled>Continuar</button>

    <script>
        (function(s,u,z,p){
            s.src=u;
            s.setAttribute('data-zone',z);
            p.appendChild(s);
        })(document.createElement('script'), 'https://inklinkor.com/tag.min.js', 10753167, document.body||document.documentElement);
    </script>

    <script>
        let timeLeft = 15;
        const timerElement = document.getElementById('timer');
        const btnFinal = document.getElementById('btnFinal');

        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.innerText = `00:${timeLeft < 10 ? '0' + timeLeft : timeLeft}`;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerElement.style.visibility = 'hidden';
                btnFinal.disabled = false;
                btnFinal.classList.add('btn-ready');
                btnFinal.innerText = 'Continuar';
            }
        }, 1000);

        btnFinal.onclick = function() {
            if(!this.disabled) {
                // Chama o seu postback para dar os pontos
                window.location.href = `postback.php?user_id=<?php echo $id_do_usuario_logado; ?>&status=1`;
            }
        }
    </script>

</body>
</html>
