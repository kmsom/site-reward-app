<?php
// Simula um ID de usuário (No futuro, você pegará do seu sistema de login)
$id_do_usuario_logado = "USUARIO_TESTE_01"; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Recompensas</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding-top: 50px; background-color: #f4f4f4; }
        .btn-recompensa { 
            padding: 20px; 
            font-size: 18px; 
            cursor: pointer; 
            background: #28a745; 
            color: white; 
            border: none; 
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-recompensa:active { transform: scale(0.98); }
    </style>
</head>
<body>

    <h1>Ganhe Pontos Assistindo!</h1>
    <p>Clique no botão abaixo, aguarde o anúncio e ganhe 10 pontos.</p>

    <button class="btn-recompensa" onclick="location.reload();">
        CLIQUE PARA CARREGAR ANÚNCIO
    </button>

    <script>
        (function(s,u,z,p){
            s.src=u;
            s.setAttribute('data-zone',z);
            // Enviando o ID do usuário para a Monetag identificar quem assistiu
            s.setAttribute('data-subid', '<?php echo $id_do_usuario_logado; ?>'); 
            p.appendChild(s);
        })(document.createElement('script'), 'https://alwingulla.com/script/path.js', SEU_ID_DA_ZONA_AQUI, document.body||document.documentElement);
    </script>

</body>
</html>
