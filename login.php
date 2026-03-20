<?php 
require 'config.php'; 
session_start(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Mechanism Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white flex items-center justify-center min-h-screen p-4">
    <div class="bg-[#16162a] w-full max-w-md p-8 rounded-2xl border border-gray-800 shadow-2xl">
        <h2 class="text-2xl font-bold text-center mb-8 text-[#00dcaa]">Bem-vindo de volta</h2>
        
        <?php if(isset($erro)): ?>
            <div class="bg-red-500/10 border border-red-500 text-red-500 p-3 rounded-lg mb-4 text-sm text-center">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <input name="email" type="email" placeholder="Seu e-mail" required 
                class="w-full bg-[#0a0a1a] border border-gray-700 rounded-lg p-3 outline-none focus:border-[#00dcaa]">
            <input name="senha" type="password" placeholder="Sua senha" required 
                class="w-full bg-[#0a0a1a] border border-gray-700 rounded-lg p-3 outline-none focus:border-[#00dcaa]">
            <button type="submit" class="w-full bg-[#00dcaa] text-[#0a0a1a] font-bold py-3 rounded-lg mt-4 shadow-lg hover:bg-[#00b88e] transition">
                ENTRAR NO PAINEL
            </button>
        </form>
        
        <p class="text-center text-gray-500 mt-6 text-sm">Não tem conta? <a href="cadastro.php" class="text-[#00dcaa] hover:underline">Cadastre-se</a></p>
    </div>
</body>
</html>
