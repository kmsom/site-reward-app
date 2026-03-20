<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Recompensas</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding-top: 50px; background-color: #f4f4f4; }
        .btn-recompensa { padding: 20px; font-size: 18px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 8px; }
    </script>
</head>
<body>

    <h1>Ganhe Pontos Assistindo!</h1>
    <p>Clique no botão abaixo para carregar o anúncio e ganhar 10 pontos.</p>

    <button class="btn-recompensa" onclick="location.reload();">
        CLIQUE PARA GANHAR RECOMPENSA
    </button>

    <?php
    // Aqui você deve recuperar o ID do usuário do seu sistema de login
    // Para teste, vamos usar um ID estático:
    $id_do_usuario_logado = "USUARIO_TESTE_01"; 
    ?>

    <script>
        (function(s,u,z,p){
            s.src=u;
            s.setAttribute('data-zone',z);
            // O subid envia o ID do usuário para o seu postback no Render
            s.setAttribute('data-subid', '<?php echo $id_do_usuario_logado; ?>'); 
            p.appendChild(s);
        })(document.createElement('script'), 'https://alwingulla.com/script/path.js', SEU_ID_DA_ZONA_AQUI, document.body||document.documentElement);
    </script>

</body>
</html>


