<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
      <link rel="stylesheet" href="public/assets/css/style.css">
    <title>Cadastrar Vendedor</title>


</head>
<body class="surface-dark">
    <div class="container" style="max-width:620px; margin:40px auto; padding:36px;">
        <div class="card" style="padding:28px;">
            <h2>Cadastro de Vendedor</h2>
           
            <form action="index.php?controller=usuario&action=store" method="POST">
       
        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        <br>
       
        <div>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <br>
       
        <div>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <br>


        <button class="btn" type="submit">Cadastrar</button>
       
            </form>
        </div>
    </div>
</body>
</html>
