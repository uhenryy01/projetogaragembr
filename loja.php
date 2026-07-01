<?php
$carros = $carros ?? [];
$perfil = $_SESSION['perfil'] ?? null;
$usuario = $_SESSION['nome'] ?? null;

function imagemCarroUrl(int $carroId): string
{
    $baseFs = __DIR__ . '/../public/uploads/carros/';
    $baseUrl = 'public/uploads/carros/';
    foreach (['jpg', 'png', 'webp'] as $ext) {
        if (file_exists($baseFs . $carroId . '.' . $ext)) {
            return $baseUrl . $carroId . '.' . $ext;
        }
    }
    return 'https://via.placeholder.com/400x250?text=Sem+imagem';
}

function bannerImageUrl(): string
{
    $bannerFiles = [
        __DIR__ . '/../img/Banner01.png',
        __DIR__ . '/../img/banner01.png',
        __DIR__ . '/../public/assets/images/banner01.png',
        __DIR__ . '/../public/assets/images/banner.svg',
    ];

    foreach ($bannerFiles as $filePath) {
        if (file_exists($filePath)) {
            return str_replace(__DIR__ . '/../', '', $filePath);
        }
    }

    return 'https://via.placeholder.com/1920x720?text=Garagem+Brasil';
}

$whatsappNumero = '';
try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT numero FROM whatsapp_config WHERE id = 1");
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    $whatsappNumero = $config ? $config['numero'] : '';
} catch (Exception $e) {
    $whatsappNumero = '';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja - Garagem Brasil</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        *{ margin:0; padding:0; box-sizing:border-box; }
        body{ background:#0a0a0a; color:#e0e0e0; font-family:Inter,system-ui,sans-serif; }

        .header-bar{
            background:#111;
            border-bottom:2px solid #c20f0f;
            padding:12px 20px;
            position:sticky;
            top:0;
            z-index:100;
            box-shadow:0 4px 20px rgba(194,15,15,0.15);
        }
        .header-inner{
            max-width:1200px;
            margin:0 auto;
            display:flex;
            align-items:center;
            justify-content:space-between;
        }
        .logo-link{ display:flex; align-items:center; gap:14px; text-decoration:none; }
        .logo-link img{ height:52px; width:auto; object-fit:contain; filter:drop-shadow(0 2px 8px rgba(194,15,15,0.3)); transition:transform .2s; }
        .logo-link img:hover{ transform:scale(1.04); }

        .header-actions{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
        .header-actions .btn{ padding:8px 18px; border-radius:10px; font-weight:600; font-size:0.9rem; }

        .hero-banner{
            position:relative;
            overflow:hidden;
            margin:28px auto;
            max-width:1200px;
            border-radius:24px;
            border:2px solid rgba(194,15,15,0.25);
            box-shadow:0 8px 40px rgba(194,15,15,0.12);
        }
        .hero-banner img{ width:100%; min-height:340px; object-fit:cover; display:block; }
        .hero-overlay{
            position:absolute; inset:0;
            background:linear-gradient(135deg, rgba(10,10,10,0.7) 0%, rgba(194,15,15,0.2) 100%);
        }
        .hero-content{
            position:absolute; inset:0;
            display:flex; flex-direction:column;
            justify-content:center; align-items:flex-start;
            padding:48px;
        }
        .hero-content h1{
            margin:0; font-size:3.2rem;
            color:#fff; text-transform:uppercase;
            letter-spacing:0.08em; font-weight:900;
            text-shadow:0 4px 20px rgba(0,0,0,0.5);
        }
        .hero-content h1 span{ color:#c20f0f; }
        .hero-content p{
            margin:16px 0 0; color:rgba(255,255,255,0.85);
            font-size:1.1rem; max-width:560px; line-height:1.5;
        }

        .cars-grid{
            max-width:1200px; margin:0 auto; padding:0 16px 40px;
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
            gap:24px;
        }

        .car-card{
            background:#141414;
            border-radius:18px;
            overflow:hidden;
            border:1px solid rgba(255,255,255,0.06);
            transition:all .25s;
            display:flex; flex-direction:column;
        }
        .car-card:hover{
            border-color:rgba(194,15,15,0.4);
            box-shadow:0 8px 32px rgba(194,15,15,0.15);
            transform:translateY(-3px);
        }
        .car-card img{ width:100%; height:200px; object-fit:cover; }
        .car-card-body{ padding:18px; flex:1; display:flex; flex-direction:column; }
        .car-card-body h2{ margin:0 0 6px; font-size:1.15rem; color:#fff; font-weight:700; }
        .car-card-body .car-sub{ margin:0 0 8px; color:#888; font-size:0.9rem; }
        .car-card-body .car-price{
            margin:0 0 12px;
            color:#c20f0f;
            font-weight:800;
            font-size:1.4rem;
            letter-spacing:-0.02em;
        }
        .car-card-body .car-desc{
            margin:0 0 16px;
            color:#999;
            line-height:1.5;
            font-size:0.92rem;
            flex:1;
        }
        .car-card-actions{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            padding-top:12px;
            border-top:1px solid rgba(255,255,255,0.06);
        }
        .car-card-actions .btn-contact{
            background:#25D366; color:#111; border:none; padding:8px 20px;
            border-radius:24px; font-weight:700; font-size:0.85rem;
            cursor:pointer; transition:all .2s;
            display:flex; align-items:center; gap:6px;
            text-decoration:none;
        }
        .car-card-actions .btn-contact:hover{ background:#20bd5a; transform:translateY(-1px); }
        .car-card-actions .btn-outline{
            background:transparent; color:#ccc; border:1px solid rgba(255,255,255,0.15);
            padding:8px 20px; border-radius:24px; font-weight:600; font-size:0.85rem;
            cursor:pointer; text-decoration:none; transition:all .2s;
            display:inline-flex; align-items:center;
        }
        .car-card-actions .btn-outline:hover{ border-color:#c20f0f; color:#c20f0f; }

        .empty-state{ text-align:center; padding:80px 24px; }
        .empty-state h2{ margin:0 0 12px; color:#fff; }

        .contato-overlay{
            position:fixed; inset:0;
            background:rgba(0,0,0,0.7);
            z-index:2000;
            display:none;
            align-items:center;
            justify-content:center;
            backdrop-filter:blur(4px);
        }
        .contato-overlay.open{ display:flex; }
        .contato-modal{
            background:#1a1a1a;
            border:1px solid rgba(255,255,255,0.1);
            border-radius:24px;
            width:460px;
            max-width:calc(100vw - 32px);
            max-height:calc(100vh - 40px);
            overflow-y:auto;
            padding:28px;
            box-shadow:0 20px 60px rgba(0,0,0,0.5);
            animation:modalIn .25s ease;
        }
        @keyframes modalIn{ from{ opacity:0; transform:scale(0.95) translateY(10px); } to{ opacity:1; transform:scale(1) translateY(0); } }
        .contato-modal-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:20px;
        }
        .contato-modal-header h2{ margin:0; font-size:1.2rem; display:flex; align-items:center; gap:8px; }
        .contato-modal-header .close-btn{
            background:none; border:none; color:#888;
            font-size:1.5rem; cursor:pointer; padding:4px; line-height:1;
        }
        .contato-modal-header .close-btn:hover{ color:#fff; }
        .contato-modal .car-info{
            background:rgba(255,255,255,0.04);
            border-radius:14px;
            padding:14px;
            margin-bottom:20px;
            border:1px solid rgba(255,255,255,0.06);
        }
        .contato-modal .car-info strong{ color:#c20f0f; }
        .contato-modal .form-group{ margin-bottom:16px; }
        .contato-modal .form-group label{ display:block; margin-bottom:6px; font-size:0.9rem; font-weight:500; }
        .contato-modal .form-group label .required{ color:#c20f0f; }
        .contato-modal .form-group input,
        .contato-modal .form-group textarea{
            width:100%;
            padding:12px 14px;
            border-radius:12px;
            background:rgba(255,255,255,0.06);
            border:1px solid rgba(255,255,255,0.1);
            color:#fff;
            font-size:0.95rem;
        }
        .contato-modal .form-group input:focus,
        .contato-modal .form-group textarea:focus{
            border-color:#25D366;
            outline:none;
        }
        .contato-modal .form-group textarea{ resize:vertical; min-height:80px; }
        .contato-modal .btn-enviar{
            width:100%;
            padding:14px;
            border:none;
            border-radius:14px;
            background:#25D366;
            color:#111;
            font-weight:700;
            font-size:1rem;
            cursor:pointer;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
        }
        .contato-modal .btn-enviar:hover{ background:#20bd5a; }
        .contato-modal .btn-enviar:disabled{ opacity:0.5; cursor:not-allowed; }
        .contato-modal .unavailable-msg{
            text-align:center;
            padding:24px;
            color:var(--muted);
        }
        .contato-modal .unavailable-msg span{ font-size:3rem; display:block; margin-bottom:12px; }

        @media(max-width:768px){
            .header-inner{ flex-wrap:wrap; gap:8px; }
            .logo-link img{ height:40px; }
            .hero-content h1{ font-size:2rem; }
            .hero-content{ padding:24px; }
            .cars-grid{ grid-template-columns:1fr; }
            .contato-modal{ padding:20px; }
        }
    </style>
</head>
<body>
    <header class="header-bar">
        <div class="header-inner">
            <a href="lojaview.php" class="logo-link">
                <img src="img/logo.png" alt="Garagem Brasil">
            </a>
            <div class="header-actions">
                <?php if ($perfil === 'admin'): ?>
                    <a class="btn btn-ghost" href="index.php?controller=auth&action=dashboard" style="color:#ccc;text-decoration:none;">Admin</a>
                <?php endif; ?>
                <?php if ($usuario): ?>
                    <span style="font-size:0.85rem;color:#888;">Olá, <?= htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') ?></span>
                    <a class="btn-outline" href="index.php?controller=auth&action=logout">Sair</a>
                <?php else: ?>
                    <a class="btn-outline" href="index.php?controller=usuario&action=create">Registro</a>
                    <a class="btn-outline" href="index.php?controller=auth&action=form">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section class="hero-banner">
        <img src="<?= htmlspecialchars(bannerImageUrl(), ENT_QUOTES, 'UTF-8') ?>" alt="Banner Garagem Brasil">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Garagem <span>Brasil</span></h1>
            <p>Encontre os melhores carros à venda, com preços claros e performance de respeito.</p>
        </div>
    </section>

    <?php if (count($carros) === 0): ?>
        <div class="empty-state">
            <h2>Nenhum carro disponível ainda</h2>
            <p style="color:#888;">Quando o admin adicionar carros, eles aparecerão aqui.</p>
        </div>
    <?php else: ?>
        <div class="cars-grid">
            <?php foreach ($carros as $car): ?>
                <?php
                    $carId = (int)($car['id'] ?? 0);
                    $imgUrl = imagemCarroUrl($carId);
                    $preco = null;
                    if (isset($car['preco_atual']) && $car['preco_atual'] !== null && $car['preco_atual'] !== '') {
                        $preco = (float)$car['preco_atual'];
                    } elseif (isset($car['preco_inicial']) && $car['preco_inicial'] !== null && $car['preco_inicial'] !== '') {
                        $preco = (float)$car['preco_inicial'];
                    } else {
                        $preco = (float)($car['preco'] ?? 0);
                    }
                    $nomeCarro = $car['nome'] ?? '';
                    $marcaCarro = $car['marca'] ?? '';
                    $modeloCarro = $car['modelo'] ?? '';
                    $anoCarro = $car['ano'] ?? '';
                    $corCarro = $car['cor'] ?? '';
                ?>
                <article class="car-card">
                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($nomeCarro, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="car-card-body">
                        <h2><?= htmlspecialchars($nomeCarro, ENT_QUOTES, 'UTF-8') ?></h2>
                        <div class="car-sub"><?= htmlspecialchars($marcaCarro, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($modeloCarro, ENT_QUOTES, 'UTF-8') ?> • <?= htmlspecialchars($anoCarro, ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="car-price">R$ <?= htmlspecialchars(number_format($preco, 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="car-desc"><?= htmlspecialchars(mb_strimwidth((string)($car['descricao'] ?? ''), 0, 120, '...'), ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="car-card-actions">
                            <button class="btn-contact" onclick="openContato(<?= $carId ?>, '<?= htmlspecialchars($nomeCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars($marcaCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars($modeloCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars($anoCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars($corCarro, ENT_QUOTES) ?>', '<?= htmlspecialchars(number_format($preco, 2, ',', '.'), ENT_QUOTES) ?>')">
                                📱 Contato
                            </button>
                            <?php if ($perfil === 'admin'): ?>
                                <a class="btn-outline" href="index.php?controller=produto&action=index">Admin</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="contato-overlay" id="contatoOverlay">
        <div class="contato-modal">
            <div class="contato-modal-header">
                <h2>📱 Contato</h2>
                <button class="close-btn" onclick="closeContato()">✕</button>
            </div>

            <div id="contatoCarInfo" class="car-info" style="display:none;">
                <strong id="contatoCarName"></strong><br>
                <span id="contatoCarDetails" style="color:var(--muted); font-size:0.9rem;"></span>
            </div>

            <?php if ($whatsappNumero === ''): ?>
                <div class="unavailable-msg">
                    <span>😔</span>
                    <p>O contato via WhatsApp está indisponível no momento.</p>
                    <p style="font-size:0.85rem;">Tente novamente mais tarde.</p>
                </div>
            <?php else: ?>
                <form id="contatoForm" onsubmit="return sendWhatsApp(event)">
                    <input type="hidden" id="fieldCarroId" value="">
                    <input type="hidden" id="fieldMarca" value="">
                    <input type="hidden" id="fieldModelo" value="">
                    <input type="hidden" id="fieldAno" value="">
                    <input type="hidden" id="fieldCor" value="">
                    <input type="hidden" id="fieldPreco" value="">

                    <div class="form-group">
                        <label>Nome <span class="required">*</span></label>
                        <input type="text" id="fieldNome" required placeholder="Seu nome">
                    </div>
                    <div class="form-group">
                        <label>Telefone <span class="required">*</span></label>
                        <input type="text" id="fieldTelefone" required placeholder="(11) 99999-9999" oninput="maskTelefone(this)">
                    </div>
                    <div class="form-group">
                        <label>Mensagem <span style="color:var(--muted); font-size:0.8rem;">(opcional)</span></label>
                        <textarea id="fieldMensagem" placeholder="Digite sua mensagem..."></textarea>
                    </div>

                    <button type="submit" class="btn-enviar" id="btnEnviar">
                        Enviar para WhatsApp
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function openContato(id, nome, marca, modelo, ano, cor, preco) {
        document.getElementById('contatoCarInfo').style.display = 'block';
        document.getElementById('contatoCarName').textContent = nome;
        document.getElementById('contatoCarDetails').textContent = marca + ' ' + modelo + ' • ' + ano + ' • ' + cor + ' • R$ ' + preco;

        document.getElementById('fieldCarroId').value = id;
        document.getElementById('fieldMarca').value = marca;
        document.getElementById('fieldModelo').value = modelo;
        document.getElementById('fieldAno').value = ano;
        document.getElementById('fieldCor').value = cor;
        document.getElementById('fieldPreco').value = preco;

        document.getElementById('contatoOverlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeContato() {
        document.getElementById('contatoOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('contatoOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeContato();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeContato();
    });

    function maskTelefone(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value.length > 11) value = value.slice(0, 11);

        if (value.length > 7) {
            value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 7) + '-' + value.slice(7);
        } else if (value.length > 2) {
            value = '(' + value.slice(0, 2) + ') ' + value.slice(2);
        } else if (value.length > 0) {
            value = '(' + value;
        }
        input.value = value;
    }

    function sendWhatsApp(event) {
        event.preventDefault();

        const nome = document.getElementById('fieldNome').value.trim();
        const telefone = document.getElementById('fieldTelefone').value.trim();
        const mensagem = document.getElementById('fieldMensagem').value.trim();

        if (!nome) { alert('Informe seu nome.'); return false; }
        if (!telefone) { alert('Informe seu telefone.'); return false; }

        const marca = document.getElementById('fieldMarca').value;
        const modelo = document.getElementById('fieldModelo').value;
        const ano = document.getElementById('fieldAno').value;
        const cor = document.getElementById('fieldCor').value;
        const preco = document.getElementById('fieldPreco').value;

        const texto = 'Olá! Tenho interesse no veículo abaixo.\n\n' +
            '🚗 Veículo: ' + marca + ' ' + modelo + '\n' +
            '📅 Ano: ' + ano + '\n' +
            '🎨 Cor: ' + cor + '\n' +
            '💰 Preço: R$ ' + preco + '\n\n' +
            '👤 Nome: ' + nome + '\n' +
            '📞 Telefone: ' + telefone + '\n\n' +
            '💬 Mensagem:\n' + (mensagem || '(Não informada)');

        const numero = <?= json_encode($whatsappNumero) ?>;
        window.open('https://wa.me/55' + numero + '?text=' + encodeURIComponent(texto), '_blank');

        closeContato();
        return false;
    }
    </script>
</body>
</html>
