<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Mechanism Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0a0a1a] text-white flex items-center justify-center min-h-screen p-4">

    <div class="bg-[#16162a] w-full max-w-md p-8 rounded-2xl shadow-2xl border border-gray-800">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#00dcaa]">Mechanism <span class="text-white">Studio</span></h1>
            <p class="text-gray-400 mt-2">Crie sua conta e comece a ganhar via PIX</p>
        </div>

        <form action="cadastro.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm mb-1 text-gray-300">Nome Completo (do seu PIX)</label>
                <input name="nome" type="text" placeholder="Ex: João Silva" required
                    class="w-full bg-[#0a0a1a] border border-gray-700 rounded-lg p-3 outline-none focus:border-[#00dcaa] transition">
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-300">Seu Melhor E-mail</label>
                <input name="email" type="email" placeholder="email@exemplo.com" required
                    class="w-full bg-[#0a0a1a] border border-gray-700 rounded-lg p-3 outline-none focus:border-[#00dcaa] transition">
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-300">Chave PIX (CPF, Celular ou E-mail)</label>
                <input name="pix" type="text" placeholder="Sua chave para receber" required
                    class="w-full bg-[#0a0a1a] border border-gray-700 rounded-lg p-3 outline-none focus:border-[#00dcaa] transition">
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-300">Crie uma Senha</label>
                <input name="senha" type="password" placeholder="••••••••" required
                    class="w-full bg-[#0a0a1a] border border-gray-700 rounded-lg p-3 outline-none focus:border-[#00dcaa] transition">
            </div>

            <button type="submit" 
                class="w-full bg-[#00dcaa] text-[#0a0a1a] font-bold py-3 rounded-lg hover:bg-[#00b88e] transition transform active:scale-95 mt-4">
                CRIAR MINHA CONTA AGORA
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            Já tem uma conta? <a href="login.php" class="text-[#00dcaa] hover:underline">Entrar aqui</a>
        </p>
    </div>

</body>
</html>
