<?php
// O ID do usuário que receberá os pontos (estático para teste)
$id_do_usuario_logado = "USUARIO_TESTE_01"; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Recompensas</title>
    <style>
        body { 
            font-family: sans-serif; 
            text-align: center; 
            padding-top: 50px; 
            background-color: #f4f4f4; 
            margin: 0;
        }
        .container { padding: 20px; }
        h1 { color: #333; }
        .btn-recompensa { 
            padding: 20px 40px; 
            font-size: 20px; 
            font-weight: bold;
            cursor: pointer; 
            background: #28a745; 
            color: white; 
            border: none; 
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: transform 0.2s, background 0.2s;
        }
        .btn-recompensa:hover { background: #218838; }
        .btn-recompensa:active { transform: scale(0.95); }
        p { color: #666; font-size: 16px; margin-bottom: 30px; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Ganhe Pontos Assistindo!</h1>
        <p>Clique no botão abaixo para carregar o anúncio e liberar sua recompensa.</p>

        <button class="btn-recompensa" onclick="location.reload();">
            CLIQUE PARA GANHAR 2 PONTOS
        </button>
    </div>

    <script>
        (function(s,u,z,p){
            s.src=u;
            s.setAttribute('data-zone',z);
            // Identificação do usuário para o sistema
            s.setAttribute('data-subid', '<?php echo $id_do_usuario_logado; ?>'); 
            p.appendChild(s);
        })(document.createElement('script'), 'https://inklinkor.com/tag.min.js', 10753165, document.body||document.documentElement);
    </script>

</body>
</html>
