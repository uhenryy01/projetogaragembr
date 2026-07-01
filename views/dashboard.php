<?php
$nome = $_SESSION['nome'] ?? 'Usuário';
$perfil = $_SESSION['perfil'] ?? 'vendedor';
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="img/logo.png">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Dashboard - Garagem Brasil</title>
	<link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="surface-dark">

<div class="header">
	<div class="container header-inner">
		<div>
			<strong>Garagem Brasil</strong>
			<span class="badge">Painel</span>
		</div>
		<div class="user">
			Olá, <strong><?php echo htmlspecialchars($nome); ?></strong>
			<a class="btn btn-ghost" href="lojaview.php" style="margin-left:8px">Ir para Loja</a>
			<a class="btn btn-ghost" href="index.php?controller=auth&action=logout">Sair</a>
		</div>
	</div>
</div>

<div class="container">
	<div class="card">
		<h2 style="margin-top:0;">Bem-vindo(a), <?php echo htmlspecialchars($nome); ?>!</h2>
		<p style="color:var(--muted); margin-top:6px;">Escolha um módulo para continuar.</p>

		<div class="nav">
			<a href="index.php?controller=produto&action=index">Produtos / Categorias</a>
			<a href="index.php?controller=entrada&action=index">Entradas</a>
			<a href="index.php?controller=venda&action=index">Vendas</a>
			<a href="index.php?controller=relatorio&action=index">Relatórios</a>
			<a href="index.php?controller=whatsapp&action=config" style="border-color: #25D366; background: rgba(37,211,102,0.1);">
				📱 WhatsApp
			</a>
		</div>

		<div class="kpis" style="margin-top:18px;">
			<div class="kpi">
				<div class="label">Vendas (mês)</div>
				<div class="value">0</div>
			</div>
			<div class="kpi">
				<div class="label">Entradas (mês)</div>
				<div class="value">0</div>
			</div>
			<div class="kpi">
				<div class="label">Estoque baixo</div>
				<div class="value">0</div>
			</div>
			<div class="kpi">
				<div class="label">Produtos</div>
				<div class="value">0</div>
			</div>
		</div>

	</div>
</div>

</body>
</html>
