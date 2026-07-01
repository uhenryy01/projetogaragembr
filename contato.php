<?php
session_start();
require_once __DIR__ . '/config/db.php';

$carro = null;
$carroId = (int)($_GET['id'] ?? $_POST['carro_id'] ?? 0);
$threadId = (int)($_POST['thread_id'] ?? $_GET['thread_id'] ?? 0);
$dados = [
    'nome' => '',
    'email' => '',
    'telefone' => '',
    'mensagem' => '',
];
$errors = [];
$success = false;
$messages = [];
$thread = [];

$pdo = Database::getConnection();
ensureContatoSchema($pdo);
ensureContatoMessagesSchema($pdo);

if ($carroId > 0) {
    $stmt = $pdo->prepare("SELECT id, nome, marca, modelo, ano, cor, km, preco_atual, descricao FROM carros WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $carroId]);
    $carro = $stmt->fetch(PDO::FETCH_ASSOC);
}

$searchEmail = trim((string)($_POST['email'] ?? $_GET['email'] ?? ''));
if ($threadId <= 0 && $searchEmail !== '') {
    $sql = 'SELECT id FROM contatos WHERE email = :email';
    $params = [':email' => $searchEmail];
    if ($carroId > 0) {
        $sql .= ' AND carro_id = :carro_id';
        $params[':carro_id'] = $carroId;
    }
    $sql .= ' ORDER BY criado_em DESC LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existing) {
        $threadId = (int)$existing['id'];
    }
}

if ($threadId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM contatos WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $threadId]);
    $thread = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados['nome'] = trim((string)($_POST['nome'] ?? ''));
    $dados['email'] = trim((string)($_POST['email'] ?? ''));
    $dados['telefone'] = trim((string)($_POST['telefone'] ?? ''));
    $dados['mensagem'] = trim((string)($_POST['mensagem'] ?? ''));

    if ($dados['email'] === '' || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Informe um e-mail válido.';
    }

    if ($dados['telefone'] === '') {
        $errors[] = 'Informe um telefone ou WhatsApp.';
    }

    if ($dados['mensagem'] === '') {
        $errors[] = 'Descreva seu lance ou proposta de preço.';
    }

        if (empty($errors)) {
            $nomeCliente = $dados['nome'] !== '' ? $dados['nome'] : 'Cliente';

            if ($threadId <= 0) {
                $stmt = $pdo->prepare(
                    'INSERT INTO contatos (carro_id, nome, email, telefone, mensagem) VALUES (:carro_id, :nome, :email, :telefone, :mensagem)'
                );
                $stmt->execute([
                    ':carro_id' => $carroId > 0 ? $carroId : null,
                    ':nome' => $nomeCliente,
                    ':email' => $dados['email'],
                    ':telefone' => $dados['telefone'],
                    ':mensagem' => $dados['mensagem'],
                ]);
                $threadId = (int)$pdo->lastInsertId();
            } else {
                $stmt = $pdo->prepare('UPDATE contatos SET nome = :nome, email = :email, telefone = :telefone, mensagem = :mensagem WHERE id = :id');
                $stmt->execute([
                    ':nome' => $nomeCliente,
                    ':email' => $dados['email'],
                    ':telefone' => $dados['telefone'],
                    ':mensagem' => $dados['mensagem'],
                    ':id' => $threadId,
                ]);
            }

            $stmt = $pdo->prepare('INSERT INTO contato_mensagens (contato_id, sender, texto) VALUES (:contato_id, :sender, :texto)');
            $stmt->execute([
                ':contato_id' => $threadId,
                ':sender' => 'client',
                ':texto' => $dados['mensagem'],
            ]);

            $success = true;
            $dados['mensagem'] = '';
        }
}

if ($threadId > 0) {
    if (empty($thread)) {
        $stmt = $pdo->prepare('SELECT * FROM contatos WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $threadId]);
        $thread = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($thread) {
        $dados['nome'] = $thread['nome'];
        $dados['email'] = $thread['email'];
        $dados['telefone'] = $thread['telefone'];

        $stmt = $pdo->prepare('SELECT sender, texto, criado_em FROM contato_mensagens WHERE contato_id = :id ORDER BY criado_em ASC');
        $stmt->execute([':id' => $threadId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($messages) && !empty($thread['mensagem'])) {
            $messages[] = [
                'sender' => 'client',
                'texto' => $thread['mensagem'],
                'criado_em' => $thread['criado_em'],
            ];
        }

        $hasAdminReply = false;
        foreach ($messages as $message) {
            if ($message['sender'] === 'admin') {
                $hasAdminReply = true;
                break;
            }
        }

        if (!$hasAdminReply && !empty($thread['resposta'])) {
            $messages[] = [
                'sender' => 'admin',
                'texto' => $thread['resposta'],
                'criado_em' => $thread['respondido_em'] ?: $thread['criado_em'],
            ];
        }
    }
}

function ensureContatoSchema(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS contatos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        carro_id INT NULL,
        nome VARCHAR(150) NOT NULL,
        email VARCHAR(150) NOT NULL,
        telefone VARCHAR(80) NOT NULL,
        mensagem TEXT NOT NULL,
        resposta TEXT NULL,
        respondido_em TIMESTAMP NULL DEFAULT NULL,
        criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}

function ensureContatoMessagesSchema(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS contato_mensagens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        contato_id INT NOT NULL,
        sender VARCHAR(20) NOT NULL,
        texto TEXT NOT NULL,
        criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
        INDEX idx_contato_id (contato_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Contato - Garagem Brasil</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        .alert { margin:20px 0; padding:18px; border-radius:16px; }
        .alert-error { background:rgba(255,0,0,.12); border:1px solid rgba(255,0,0,.25); color:#fff; }
        .alert-success { background:rgba(0,255,128,.12); border:1px solid rgba(0,255,128,.25); color:#fff; }
        .form-group { margin-bottom:16px; }
        .form-group label { display:block; margin-bottom:6px; }
        .form-group input, .form-group textarea { width:100%; }
        .contact-card { border-radius:22px; padding:18px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); }
        .history-card { margin-top:24px; padding:18px; border-radius:18px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); }
        .history-item { padding:14px 0; border-bottom:1px solid rgba(255,255,255,0.08); }
        .history-item:last-child { border-bottom:0; }
        .history-item small { display:block; margin-top:8px; color:rgba(255,255,255,0.72); }
    </style>
</head>
<body>
<div class="container">
    <div class="card" style="max-width:720px; margin:40px auto;">
        <h1>Contato com o vendedor</h1>
        <p>Envie seus dados e um lance ou proposta de preço para este veículo. O vendedor poderá aceitar ou não a negociação.</p>
        <?php if ($carro): ?>
            <p>Você está enviando uma solicitação sobre:</p>
            <div class="card" style="padding:18px; margin-bottom:20px;">
                <h2><?php echo htmlspecialchars($carro['nome'] . ' ' . $carro['marca'] . ' ' . $carro['modelo'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p><strong>Ano:</strong> <?php echo htmlspecialchars($carro['ano'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Cor:</strong> <?php echo htmlspecialchars($carro['cor'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>KM:</strong> <?php echo htmlspecialchars($carro['km'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Preço:</strong> R$ <?php echo htmlspecialchars(number_format((float)$carro['preco_atual'], 2, ',', '.'), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Corrija os erros abaixo:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <strong>Solicitação enviada!</strong> Sua proposta foi registrada para o vendedor analisar.
            </div>
        <?php endif; ?>

        <div class="contact-card">
            <form method="post" action="contato.php<?= $threadId ? '?thread_id=' . $threadId : ($carroId ? '?id=' . $carroId : '') ?>">
                <input type="hidden" name="carro_id" value="<?php echo $carroId; ?>">
                <div class="form-group">
                    <label for="nome">Nome (opcional)</label>
                    <input class="input" id="nome" name="nome" type="text" value="<?php echo htmlspecialchars($dados['nome'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input class="input" id="email" name="email" type="email" required value="<?php echo htmlspecialchars($dados['email'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone / WhatsApp</label>
                    <input class="input" id="telefone" name="telefone" type="text" required value="<?php echo htmlspecialchars($dados['telefone'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="form-group">
                    <label for="mensagem">Lance ou proposta de preço</label>
                    <textarea class="input" id="mensagem" name="mensagem" rows="5" required><?php echo htmlspecialchars($dados['mensagem'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <button class="btn" type="submit"><?= $threadId > 0 ? 'Enviar nova proposta' : 'Enviar contato' ?></button>
                <input type="hidden" name="thread_id" value="<?= (int)$threadId ?>">
            </form>
        </div>

        <?php if (!empty($messages)): ?>
            <div class="history-card">
                <h3>Histórico de contato</h3>
                <?php foreach ($messages as $item): ?>
                    <div class="history-item">
                        <strong><?= $item['sender'] === 'admin' ? 'Vendedor' : 'Cliente'; ?>:</strong>
                        <p><?= nl2br(htmlspecialchars($item['texto'], ENT_QUOTES, 'UTF-8')) ?></p>
                        <small><?= htmlspecialchars(date('d/m H:i', strtotime($item['criado_em'])), ENT_QUOTES, 'UTF-8') ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p style="margin-top:20px;"><a href="lojaview.php" class="btn btn-ghost">Voltar à loja</a></p>
    </div>
</div>
</body>
</html>
