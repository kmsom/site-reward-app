<?php
session_start(); // Localiza a sessão atual
session_unset(); // Limpa todas as variáveis da sessão
session_destroy(); // Destrói a sessão no servidor

// Redireciona para a página inicial (index.php)
header("Location: index.php");
exit;
?>
