<?php
$contatos = $contatos ?? [];
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="img/logo.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mensagens - Garagem Brasil</title>
	<link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="surface-dark">

<div class="header">
	<div class="container header-inner">
		<div>
			<strong>Garagem Brasil</strong>
			<span class="badge">Painel</span>
		</div>
		<div class="user">Olá, <strong><?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário'); ?></strong>
			<a class="btn btn-ghost" href="lojaview.php" style="margin-left:8px">Ir para Loja</a>
			<a class="btn btn-ghost" href="index.php?controller=auth&action=logout">Sair</a>
		</div>
	</div>
</div>

<div class="container">
	<div class="card">
		<h2>Mensagens recebidas</h2>
<?php if (empty($contatos)): ?>
<p>Nenhuma mensagem registrada ainda.</p>
<?php else: ?>
<table class="table">
<thead>
<tr>
<th>ID</th>
<th>Carro</th>
<th>Nome</th>
<th>E-mail</th>
<th>Telefone</th>
<th>Mensagem</th>
<th>Resposta</th>
<th>Recebido em</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
<?php foreach ($contatos as $contato): ?>
<tr>
<td>#<?= (int)$contato['id'] ?></td>
<td><?= htmlspecialchars($contato['carro_nome'] ?: 'Sem carro') ?></td>
<td><?= htmlspecialchars($contato['nome']) ?></td>
<td><?= htmlspecialchars($contato['email']) ?></td>
<td><?= htmlspecialchars($contato['telefone']) ?></td>
<td><?= nl2br(htmlspecialchars($contato['mensagem'])) ?></td>
<td><?= $contato['resposta'] ? 'Respondido' : 'Pendente' ?></td>
<td><?= htmlspecialchars($contato['criado_em']) ?></td>
<td><a class="btn" href="index.php?controller=contato&action=thread&id=<?= (int)$contato['id'] ?>">Ver conversa</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</div>
</div>
</body>
</html>
