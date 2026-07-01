<?php
$editar = $editar ?? null;
$produtos = $produtos ?? [];

function imagemProdutoUrl(int $produtoId): string
{
    $baseFs = __DIR__ . "/../public/uploads/carros/";
    $baseUrl = "public/uploads/carros/";
    foreach (['jpg','png','webp'] as $ext) {
        if (file_exists($baseFs . $produtoId . '.' . $ext)) {
            return $baseUrl . $produtoId . '.' . $ext;
        }
    }
    return "https://via.placeholder.com/140x100?text=Sem+imagem";
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<link rel="icon" type="image/png" href="img/logo.png">
<title>Admin - Carros</title>
<link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="surface-dark">
<div class="header">
    <div class="container header-inner">
        <div>
            <strong>Garagem Brasil</strong>
            <span class="badge">Admin Carros</span>
        </div>
        <div class="user">
            Olá, <strong><?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?></strong>
            <a class="btn btn-ghost" href="lojaview.php" style="margin-left:8px">Ir para Loja</a>
            <a class="btn btn-ghost" href="index.php?controller=auth&action=logout">Sair</a>
        </div>
    </div>
</div>
<div class="container grid">
<div class="card">
<h2><?= !empty($editar) ? "Editar Carro #".(int)$editar['id'] : "Cadastrar Carro" ?></h2>
<form method="post" action="index.php?controller=produto&action=salvar" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= !empty($editar) ? (int)$editar['id'] : 0 ?>">
<div class="form-group">
<label>Nome</label>
<input class="input" type="text" name="nome" required value="<?= !empty($editar) ? htmlspecialchars($editar['nome'] ?? '') : '' ?>">
</div>
<div class="form-group">
<label>Marca</label>
<input class="input" type="text" name="marca" required value="<?= !empty($editar) ? htmlspecialchars($editar['marca'] ?? '') : '' ?>">
</div>
<div class="form-group">
<label>Modelo</label>
<input class="input" type="text" name="modelo" required value="<?= !empty($editar) ? htmlspecialchars($editar['modelo'] ?? '') : '' ?>">
</div>
<div class="form-group">
<label>Ano</label>
<input class="input" type="number" name="ano" min="1900" max="2100" value="<?= !empty($editar) ? (int)$editar['ano'] : '' ?>">
</div>
<div class="form-group">
<label>Cor</label>
<input class="input" type="text" name="cor" value="<?= !empty($editar) ? htmlspecialchars($editar['cor'] ?? '') : '' ?>">
</div>
<div class="form-group">
<label>KM</label>
<input class="input" type="number" name="km" min="0" value="<?= !empty($editar) ? (int)$editar['km'] : '' ?>">
</div>
<div class="form-group">
<label>Preço</label>
<input class="input" type="text" inputmode="decimal" pattern="[0-9.,]*" name="preco" placeholder="Ex: 16.000,00 ou 16000.00" required value="<?= !empty($editar) ? htmlspecialchars($editar['preco'] ?? '') : '' ?>">
<small class="muted">Use ponto ou vírgula. Ex: 16.000,00 ou 16000.00</small>
</div>
<div class="form-group">
<label>Preço atual (opcional)</label>
<input class="input" type="text" inputmode="decimal" pattern="[0-9.,]*" name="preco_atual" placeholder="Ex: 16.000,00 ou 16000.00" value="<?= !empty($editar) ? htmlspecialchars($editar['preco_atual'] ?? '') : '' ?>">
<small class="muted">Valor em reais usando ponto ou vírgula.</small>
</div>
<div class="form-group">
<label>Descrição (opcional)</label>
<textarea class="input" name="descricao" rows="3"><?= !empty($editar) ? htmlspecialchars($editar['descricao'] ?? '') : '' ?></textarea>
</div>
<div class="form-group">
<label>Imagem do carro (opcional)</label>
<input class="input" type="file" name="imagem" accept="image/png, image/jpeg, image/webp">
<small class="muted">JPG, PNG ou WEBP até 2MB. Imagem salva pelo ID do carro.</small>
</div>
<div class="actions">
<button class="btn btn-primary" type="submit">Salvar</button>
<a class="btn" href="index.php?controller=produto&action=index">Novo carro</a>
</div>
</form>
</div>
<div class="card">
<h2>Lista de Produtos</h2>
<table class="table">
<thead>
<tr>
<th>Imagem</th>
<th>ID</th>
<th>Nome</th>
<th>Status</th>
<th style="width:220px;">Ações</th>
</tr>
</thead>
<tbody>
<?php foreach ($produtos as $p): ?>
<tr>
<td>
<img class="thumb" src="<?= imagemProdutoUrl((int)$p['id']) ?>" alt="produto">
</td>
<td>#<?= (int)$p['id'] ?></td>
<td><?= htmlspecialchars($p['nome']) ?></td>
<td>
<?= ((int)$p['ativo'] === 1) ? '<span class="tag ok">Ativo</span>' : '<span class="tag
off">Inativo</span>' ?>
</td>
<td>
<a class="btn" href="index.php?controller=produto&action=index&id=<?= (int)$p['id']
?>">Editar</a>
<?php if ((int)$p['ativo'] === 1): ?>
<a class="btn btn-danger"
href="index.php?controller=produto&action=toggle&id=<?= (int)$p['id'] ?>&ativo=0"
onclick="return confirm('Inativar este produto?')">Inativar</a>
<?php else: ?>
<a class="btn btn-success"
href="index.php?controller=produto&action=toggle&id=<?= (int)$p['id'] ?>&ativo=1">Ativar</a>
<?php endif; ?>
<a class="btn btn-danger"
href="index.php?controller=produto&action=deletar&id=<?= (int)$p['id'] ?>"
onclick="return confirm('DELETAR permanentemente? Não há volta!')">Excluir</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</body>
</html>
