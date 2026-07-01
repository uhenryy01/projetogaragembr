<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    public function create()
    {
       header("Location: index.php?controller=auth&action=form&cadastro=1");
       exit;
    }

    public function store()
    {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha_plana = $_POST['senha'] ?? '';

        if (empty($nome) || empty($email) || empty($senha_plana)) {
            die("Por favor, preencha todos os campos.");
        }

        $senha_hash = password_hash($senha_plana, PASSWORD_DEFAULT);

        $usuarioModel = new Usuario();

        $existente = $usuarioModel->buscarPorEmail($email);
        if ($existente) {
            die("Este e-mail já está cadastrado. <a href='index.php?controller=auth&action=form'>Voltar e tentar outro e-mail</a>.");
        }

        // Se o nome ou e-mail contiver "adm", registra como administrador
        $tipo = (stripos($nome, 'adm') !== false || stripos($email, 'adm') !== false) ? 'admin' : 'cliente';
        $sucesso = $usuarioModel->cadastrar($nome, $email, $senha_hash, $tipo);

        if ($sucesso) {
            header("Location: index.php?controller=auth&action=form");
            exit;
        } else {
            die("Erro ao cadastrar no banco de dados.");
        }
    }
}
