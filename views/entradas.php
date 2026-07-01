<?php
$nome = $_SESSION['nome'] ?? 'Usuário';
$entradas = $entradas ?? [];
$carros = $carros ?? [];
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="img/logo.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Entradas - Garagem Brasil</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="surface-dark">

<div class="header">
    <div class="container header-inner">
        <div>
            <strong>Garagem Brasil</strong>
            <span class="badge">Entradas</span>
        </div>
        <div class="user">
            Olá, <strong><?= htmlspecialchars($nome) ?></strong>
            <a class="btn btn-ghost" href="index.php?controller=auth&action=dashboard" style="margin-left:8px">Voltar</a>
            <a class="btn btn-ghost" href="index.php?controller=auth&action=logout">Sair</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <h2 style="margin-top:0;">Registrar Entrada</h2>
        <form method="POST" action="index.php?controller=entrada&action=salvar" style="display:flex;flex-wrap:wrap;gap:10px;align-items:end;">
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Carro</label>
                <select name="carro_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($carros as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome'] . ' - ' . ($c['marca'] ?? '') . ' ' . ($c['modelo'] ?? '')) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Quantidade</label>
                <input type="number" name="quantidade" value="1" min="1" required>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Preço de Custo (R$)</label>
                <input type="text" name="preco_custo" placeholder="0,00" required>
            </div>
            <div>
                <label style="display:block;font-size:0.85rem;color:var(--muted);margin-bottom:4px;">Fornecedor</label>
                <input type="text" name="fornecedor" placeholder="Opcional">
            </div>
            <button class="btn" type="submit">Registrar</button>
        </form>
    </div>

    <div class="card" style="margin-top:16px;">
        <h2 style="margin-top:0;">Histórico de Entradas</h2>
        <?php if (empty($entradas)): ?>
            <p style="color:var(--muted)">Nenhuma entrada registrada.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Carro</th>
                            <th>Qtd</th>
                            <th>Valor</th>
                            <th>Total</th>
                            <th>Fornecedor</th>
                            <th>Data</th>
                            <th>Usuário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entradas as $e): ?>
                        <tr>
                            <td><?= $e['id'] ?></td>
                            <td><?= htmlspecialchars($e['carro_nome'] ?? '') ?></td>
                            <td><?= $e['quantidade'] ?></td>
                            <td>R$ <?= number_format($e['preco_custo'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($e['preco_custo'] * $e['quantidade'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($e['fornecedor'] ?? '-') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($e['data_entrada'])) ?></td>
                            <td><?= htmlspecialchars($e['usuario_nome'] ?? '') ?></td>
                            <td>
                                <a class="btn btn-sm btn-danger" href="index.php?controller=entrada&action=deletar&id=<?= $e['id'] ?>" onclick="return confirm('Excluir entrada?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
