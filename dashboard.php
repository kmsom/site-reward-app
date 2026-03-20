<?php 
require 'config.php'; session_start(); 
if(!isset($_SESSION['user_id'])) header("Location: login.php");
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]); $u = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Mechanism Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white pb-20">
    <div class="p-6 flex justify-between items-center">
        <div>
            <p class="text-gray-400 text-sm">Bem-vindo,</p>
            <h2 class="text-xl font-bold"><?php echo explode(' ', $u['nome_pix'])[0]; ?> 👋</h2>
        </div>
        <a href="logout.php" class="bg-red-500/10 text-red-500 p-2 rounded-lg text-sm">Sair</a>
    </div>

    <div class="px-6">
        <div class="bg-gradient-to-br from-[#00dcaa] to-[#00b88e] p-8 rounded-3xl text-[#0a0a1a] shadow-xl relative overflow-hidden">
            <p class="font-medium opacity-80">Saldo Disponível</p>
            <h1 class="text-4xl font-black mt-1"><?php echo formatarGrana($u['saldo_pontos']); ?></h1>
            <div class="absolute -right-4 -bottom-4 bg-white/20 w-24 h-24 rounded-full"></div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 p-6">
        <a href="missoes.php" class="bg-[#16162a] p-6 rounded-2xl border border-gray-800 text-center">
            <span class="text-3xl block mb-2">📺</span>
            <span class="font-bold">Missões</span>
        </a>
        <a href="sacar.php" class="bg-[#16162a] p-6 rounded-2xl border border-gray-800 text-center">
            <span class="text-3xl block mb-2">💸</span>
            <span class="font-bold">Sacar</span>
        </a>
    </div>

    <div class="mx-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl text-blue-400 text-sm text-center">
        Faltam <b><?php echo formatarGrana(10 - $u['saldo_pontos']); ?></b> para o seu próximo saque!
    </div>

</body>
</html>
