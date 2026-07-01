<?php
$showCadastro = isset($_GET['cadastro']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Garagem Brasil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        *{ margin:0; padding:0; box-sizing:border-box; }
        body{
            background:#000;
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family:Inter, Arial, Helvetica, sans-serif;
        }
        .bg-full{
            position:fixed;
            inset:0;
            width:100%;
            height:100%;
            object-fit:cover;
            z-index:0;
        }
        .overlay{
            position:fixed;
            inset:0;
            background:rgba(0,0,0,0.55);
            z-index:1;
        }
        .modal-wrap{
            position:relative;
            z-index:2;
            width:100%;
            max-width:420px;
            padding:0 16px;
        }
        .modal{
            background:rgba(20,20,20,0.92);
            backdrop-filter:blur(12px);
            border:1px solid rgba(255,255,255,0.08);
            border-radius:24px;
            padding:36px 40px;
            box-shadow:0 24px 60px rgba(0,0,0,0.6);
            display:none;
        }
        .modal.active{ display:block; }
        .modal h1{ font-size:1.6rem; margin-bottom:4px; color:#fff; }
        .modal small{ color:rgba(255,255,255,0.5); }
        .modal .brand{ display:flex; align-items:center; gap:14px; margin-bottom:24px; }
        .modal .logo-icon{ width:48px; height:48px; object-fit:contain; flex-shrink:0; }
        .modal label{ display:block; margin:14px 0 5px; color:rgba(255,255,255,0.8); font-size:0.9rem; }
        .modal input{
            width:100%; padding:12px 16px; border-radius:14px;
            background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1);
            color:#fff; font-size:1rem;
        }
        .modal input:focus{ border-color:var(--accent); outline:none; box-shadow:0 0 0 3px rgba(194,15,15,0.15); }
        .modal .actions{ display:flex; gap:10px; margin-top:18px; justify-content:flex-end; }
        .modal .btn{ padding:12px 24px; border-radius:14px; border:none; cursor:pointer; font-weight:600; font-size:0.95rem; }
        .modal .btn-primary{ background:linear-gradient(90deg,var(--accent),#8a0909); color:#fff; }
        .modal .toggle-link{ margin-top:20px; text-align:center; font-size:0.9rem; color:rgba(255,255,255,0.5); }
        .modal .toggle-link a{ color:#ff3d3d; cursor:pointer; text-decoration:none; }
        .modal .toggle-link a:hover{ text-decoration:underline; }
    </style>
</head>
<body>
    <img class="bg-full" src="img/banner.2.jpg" alt="Garagem Brasil">
    <div class="overlay"></div>

    <div class="modal-wrap">
        <!-- LOGIN -->
        <div class="modal<?= $showCadastro ? '' : ' active' ?>" id="modal-login">
            <div class="brand">
                <img class="logo-icon" src="img/logo.png" alt="Logo">
                <div>
                    <h1>Garagem Brasil</h1>
                    <small>Acesso ao sistema</small>
                </div>
            </div>
            <form method="post" action="index.php?controller=auth&action=login">
                <label>E-mail</label>
                <input type="email" name="email" required autocomplete="username">
                <label>Senha</label>
                <input type="password" name="senha" required autocomplete="current-password">
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Entrar</button>
                </div>
            </form>
            <div class="toggle-link">
                Não tem conta? <a onclick="showModal('cadastro')">Cadastre-se</a>
            </div>
        </div>

        <!-- CADASTRO -->
        <div class="modal<?= $showCadastro ? ' active' : '' ?>" id="modal-cadastro">
            <div class="brand">
                <img class="logo-icon" src="img/logo.png" alt="Logo">
                <div>
                    <h1>Garagem Brasil</h1>
                    <small>Criar conta</small>
                </div>
            </div>
            <form method="post" action="index.php?controller=usuario&action=store">
                <label>Nome</label>
                <input type="text" name="nome" required>
                <label>E-mail</label>
                <input type="email" name="email" required>
                <label>Senha</label>
                <input type="password" name="senha" required>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Cadastrar</button>
                </div>
            </form>
            <div class="toggle-link">
                Já tem conta? <a onclick="showModal('login')">Fazer login</a>
            </div>
        </div>
    </div>

    <script>
    function showModal(id){
        document.querySelectorAll('.modal').forEach(function(m){ m.classList.remove('active'); });
        document.getElementById('modal-'+id).classList.add('active');
    }
    <?php if ($showCadastro): ?>
    showModal('cadastro');
    <?php endif; ?>
    </script>
</body>
</html>
