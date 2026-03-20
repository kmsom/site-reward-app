<?php
session_start();

// Se o utilizador já tiver sessão aberta, manda direto para os ganhos
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanism Studio - Ganhe por Assistir</title>
    <style>
        body { background: #0a0a1a; color: white; font-family: 'Inter', sans-serif; margin: 0; text-align: center; display: flex; flex-direction: column; justify-content: center; height: 100vh; }
        .hero { padding: 20px; }
        h1 { color: #22d3ee; font-size: 42px; margin-bottom: 10px; }
        p { color: #64748b; font-size: 18px; margin-bottom: 30px; }
        .btn-main { 
            background: linear-gradient(90deg, #00dcaa, #22d3ee); 
            color: #0a0a1a; padding: 18px 40px; border-radius: 50px; 
            text-decoration: none; font-weight: bold; font-size: 20px;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.4);
        }
        .login-link { margin-top: 25px; display: block; color: #00dcaa; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <div class="hero">
        <div style="font-size: 60px; margin-bottom: 20px;">💰</div>
        <h1>Ganhe Dinheiro Assistindo Anúncios</h1>
        <p>A plataforma mais simples para gerar renda extra diária via PIX.</p>
        
        <br><br>
        <a href="cadastro.php" class="btn-main">COMEÇAR AGORA</a>
        
        <a href="login.php" class="login-link">Já tenho uma conta (Entrar)</a>
    </div>

    <div style="position: absolute; bottom: 20px; width: 100%; color: #1f2937; font-size: 12px;">
        &copy; 2026 Mechanism Studio Dev LTD
    </div>

</body>
</html>
