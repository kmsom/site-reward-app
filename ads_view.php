<?php 
require 'config.php';
session_start();
$mid = $_GET['m']; // ID da missão (ex: m1, m2...)
?>
<!DOCTYPE html>
<html>
<head>
    <title>Carregando Anúncio...</title>
    <script src="https://alwingulla.com/tag.min.js" data-zone="10753165" async></script>
</head>
<body style="background:#0a0a1a; color:white; text-align:center; padding-top:50px;">

    <div id="timer" style="font-size:24px;">Aguarde 20 segundos para coletar...</div>

    <script>
        let tempo = 20;
        let contagem = setInterval(() => {
            if(tempo > 0) {
                tempo--;
                document.getElementById('timer').innerText = "Assista por mais " + tempo + "s";
            } else {
                clearInterval(contagem);
                // Quando o tempo acaba, aparece o botão que chama o postback.php
                document.getElementById('timer').innerHTML = 
                    '<a href="postback.php?m=<?php echo $mid; ?>" style="background:#00dcaa; color:#000; padding:15px; text-decoration:none; font-weight:bold; border-radius:10px;">RECEBER R$ 0,50 AGORA</a>';
            }
        }, 1000);
    </script>
</body>
</html>
