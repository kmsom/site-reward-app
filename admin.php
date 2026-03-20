<?php 
require 'config.php'; 
session_start(); 

// 1. TRAVA DE SEGURANÇA (IDs 1 e 2)
$admins = [1, 2];
if(!isset($_SESSION['user_id']) || !in_array($_SESSION['user_id'], $admins)) {
    header("Location: dashboard.php"); exit;
}

// 2. PROCESSAMENTO DE AÇÕES
// Marcar Saque como Pago
if(isset($_GET['pagar'])) {
    $pdo->prepare("UPDATE saques SET status = 'pago' WHERE id = ?")->execute([$_GET['pagar']]);
    header("Location: admin.php?msg=pago");
}

// Adicionar Nova Rede de Anúncio
if(isset($_POST['add_rede'])) {
    $nome = $_POST['nome_rede'];
    $link = $_POST['link_anuncio'];
    $pdo->prepare("INSERT INTO redes_anuncios (nome_rede, link_anuncio) VALUES (?,?)")->execute([$nome, $link]);
    header("Location: admin.php?msg=rede_add");
}

// Excluir Rede de Anúncio
if(isset($_GET['del_rede'])) {
    $pdo->prepare("DELETE FROM redes_anuncios WHERE id = ?")->execute([$_GET['del_rede']]);
    header("Location: admin.php?msg=rede_del");
}

// 3. BUSCA DE DADOS
$saques = $pdo->query("SELECT s.*, u.nome_pix, u.chave_pix FROM saques s JOIN usuarios u ON s.usuario_id = u.id WHERE s.status = 'pendente' ORDER BY s.data_pedido DESC")->fetchAll();
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC LIMIT 5")->fetchAll();
$redes = $pdo->query("SELECT * FROM redes_anuncios ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Mechanism Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #0a0a1a; color: white; font-family: sans-serif; }
        .card { background: #16162a; border: 1px solid #1f2937; border-radius: 1.5rem; }
    </style>
</head>
<body class="p-4 md:p-8">

    <div class="max-w-6xl mx-auto">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black text-[#00dcaa]">PAINEL <span class="text-white">ADMIN</span></h1>
                <p class="text-gray-500 text-sm">Bem-vindo, Chefe. Gerencie seu império.</p>
            </div>
            <a href="dashboard.php" class="bg-gray-800 px-4 py-2 rounded-xl text-sm hover:bg-white hover:text-black transition font-bold">Sair do Admin</a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                <section class="card p-6">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-[#00dcaa] rounded-full"></span> Saques Pendentes
                    </h2>
                    
                    <div class="space-y-4">
                        <?php if(!$saques): ?> <p class="text-gray-600 italic">Nenhum saque no momento...</p> <?php endif; ?>
                        <?php foreach($saques as $s): ?>
                        <div class="bg-[#0a0a1a] p-4 rounded-2xl flex justify-between items-center border border-gray-800">
                            <div>
                                <p class="font-bold"><?php echo $s['nome_pix']; ?></p>
                                <p class="text-[#00dcaa] text-xs font-mono"><?php echo $s['chave_pix']; ?></p>
                                <p class="text-2xl font-black mt-1">R$ <?php echo number_format($s['valor'], 2, ',', '.'); ?></p>
                            </div>
                            <a href="admin.php?pagar=<?php echo $s['id']; ?>" class="bg-[#00dcaa] text-black font-bold px-4 py-2 rounded-lg text-sm">PAGAR</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="card p-6">
                    <h2 class="text-xl font-bold mb-6">Rodízio de Anúncios (<?php echo count($redes); ?> Redes)</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-gray-500 border-b border-gray-800">
                                    <th class="pb-3">Rede</th>
                                    <th class="pb-3">Link</th>
                                    <th class="pb-3 text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                <?php foreach($redes as $r): ?>
                                <tr>
                                    <td class="py-3 font-bold"><?php echo $r['nome_rede']; ?></td>
                                    <td class="py-3 text-gray-500 truncate max-w-[150px]"><?php echo $r['link_anuncio']; ?></td>
                                    <td class="py-3 text-right">
                                        <a href="admin.php?del_rede=<?php echo $r['id']; ?>" class="text-red-500 hover:underline">Remover</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="space-y-8">
                <section class="card p-6 border-[#00dcaa]/30">
                    <h3 class="font-bold text-[#00dcaa] mb-4 uppercase text-xs tracking-widest">Nova Rede de Anúncio</h3>
                    <form method="POST" class="space-y-3">
                        <input name="nome_rede" placeholder="Nome (Ex: Monetag 01)" required class="w-full bg-[#0a0a1a] border border-gray-800 p-3 rounded-xl outline-none focus:border-[#00dcaa]">
                        <input name="link_anuncio" placeholder="URL do Direct Link" required class="w-full bg-[#0a0a1a] border border-gray-800 p-3 rounded-xl outline-none focus:border-[#00dcaa]">
                        <button name="add_rede" class="w-full bg-[#00dcaa] text-black font-bold py-3 rounded-xl shadow-lg shadow-[#00dcaa]/20">ADICIONAR AO SORTEIO</button>
                    </form>
                </section>

                <section class="card p-6">
                    <h3 class="font-bold mb-4">Novos Usuários</h3>
                    <div class="space-y-4">
                        <?php foreach($usuarios as $user): ?>
                        <div class="text-xs border-b border-gray-800 pb-2">
                            <p class="font-bold text-gray-300"><?php echo $user['email']; ?></p>
                            <p class="text-gray-600 font-mono">IP: <?php echo $user['ip_cadastro']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>

        </div>
    </div>

</body>
</html>
