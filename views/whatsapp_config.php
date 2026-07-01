<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configurações do WhatsApp - Garagem Brasil</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        .whatsapp-card { max-width:580px; margin:40px auto; }
        .whatsapp-card .form-group { margin-bottom:20px; }
        .whatsapp-card label { display:block; margin-bottom:8px; font-weight:600; }
        .whatsapp-card .input-group { display:flex; gap:10px; align-items:center; }
        .whatsapp-card .input-group input { flex:1; }
        .whatsapp-card .help-text { font-size:0.85rem; color:var(--muted); margin-top:6px; }
        .whatsapp-card .btn-save { background:#25D366; color:#111; border:none; padding:12px 32px; border-radius:14px; font-weight:700; font-size:1rem; cursor:pointer; }
        .whatsapp-card .btn-save:hover { background:#20bd5a; }
        .alert-success { background:rgba(37,211,102,0.15); border:1px solid rgba(37,211,102,0.3); color:#25D366; padding:14px 18px; border-radius:14px; margin-bottom:20px; font-weight:600; display:<?= $saved ? 'block' : 'none' ?>; }
        .current-number { background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:16px; margin-bottom:20px; }
        .current-number strong { display:block; font-size:1.2rem; color:#25D366; margin-top:4px; }
        .current-number .empty-msg { color:var(--muted); font-style:italic; }
    </style>
</head>
<body class="surface-dark">
    <div class="header">
        <div class="container header-inner">
            <div>
                <strong>Garagem Brasil</strong>
                <span class="badge">WhatsApp</span>
            </div>
            <div class="user">
                Olá, <strong><?= htmlspecialchars($_SESSION['nome'] ?? 'Admin') ?></strong>
                <a class="btn btn-ghost" href="lojaview.php" style="margin-left:8px">Ir para Loja</a>
                <a class="btn btn-ghost" href="index.php?controller=auth&action=logout">Sair</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card whatsapp-card">
            <h1 style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:1.5rem;">📱</span>
                Configurações do WhatsApp
            </h1>
            <p style="color:var(--muted); margin-bottom:24px;">
                Configure o número de WhatsApp que será usado para receber os contatos dos clientes.
            </p>

            <div class="alert-success" id="successAlert">
                Configuração salva com sucesso!
            </div>

            <div class="current-number">
                Número atual:
                <strong>
                    <?= $numero ? '+55 ' . substr($numero, 0, 2) . ' ' . substr($numero, 2) : '<span class="empty-msg">Nenhum número configurado</span>' ?>
                </strong>
            </div>

            <form method="post" action="index.php?controller=whatsapp&action=config" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="numero">Número do WhatsApp</label>
                    <div class="input-group">
                        <span style="color:var(--muted); font-weight:600;">+55</span>
                        <input
                            type="text"
                            id="numero"
                            name="numero"
                            class="input"
                            placeholder="11999999999"
                            value="<?= htmlspecialchars($numero) ?>"
                            maxlength="20"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                        >
                    </div>
                    <div class="help-text">
                        Digite apenas números — inclua DDD e o número com 8 ou 9 dígitos (ex: 11999999999).
                    </div>
                </div>

                <div style="display:flex; gap:12px; align-items:center;">
                    <button type="submit" class="btn-save">Salvar</button>
                    <a class="btn btn-ghost" href="index.php?controller=auth&action=dashboard">Voltar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    function validateForm() {
        const input = document.getElementById('numero');
        const num = input.value.replace(/[^0-9]/g, '');
        if (num.length > 0 && num.length < 10) {
            alert('O número deve ter pelo menos 10 dígitos (DDD + número).');
            return false;
        }
        return true;
    }

    <?php if ($saved): ?>
    setTimeout(() => {
        const alert = document.getElementById('successAlert');
        if (alert) alert.style.display = 'none';
    }, 4000);
    <?php endif; ?>
    </script>
</body>
</html>
