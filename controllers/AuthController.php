<?php
require_once __DIR__ . '/../models/Usuario.php';
class AuthController
{
public function form()
{
require_once __DIR__ . '/../views/login.php';
}
public function login()
{
$email = strtolower(trim($_POST['email'] ?? ''));
$senha = $_POST['senha'] ?? '';

if ($email === 'adm@gmail.com') {
    if ($senha !== '12345') {
        die("Usuário inválido.");
    }

    $usuarioModel = new Usuario();
    $admin = $usuarioModel->buscarPorEmail($email);
    if (!$admin) {
        $senhaHash = password_hash('12345', PASSWORD_DEFAULT);
        $usuarioModel->cadastrar('Administrador', $email, $senhaHash, 'admin');
        $admin = $usuarioModel->buscarPorEmail($email);
    }

    if (!$admin) {
        die("Erro ao autenticar administrador.");
    }

    $_SESSION['usuario_id'] = $admin['id'];
    $_SESSION['usuario_email'] = $admin['email'] ?? $email;
    $_SESSION['perfil'] = 'admin';
    $_SESSION['nome'] = $admin['nome'] ?? 'Administrador';
    header("Location: index.php?controller=auth&action=dashboard");
    exit;
}

$usuarioModel = new Usuario();
$user = $usuarioModel->buscarPorEmail($email);
if (!$user) {
    die("Usuário inválido.");
}
if (!password_verify($senha, $user['senha'])) {
    die("Senha inválida.");
}
$_SESSION['usuario_id'] = $user['id'];
$_SESSION['usuario_email'] = $user['email'];
$_SESSION['perfil'] = 'cliente';
$_SESSION['nome'] = $user['nome'];
header("Location: lojaview.php");
exit;
}
public function dashboard()
{
    $this->check();
    $this->onlyAdmin();
    require_once __DIR__ . '/../views/dashboard.php';
}
public function logout()
{
session_destroy();
header("Location: index.php?controller=auth&action=form");
exit;
}
public function check()
{
if (!isset($_SESSION['usuario_id'])) {
header("Location: index.php?controller=auth&action=form");
exit;
} }
public function onlyAdmin()
{
$this->check();
if (($_SESSION['perfil'] ?? '') !== 'admin') {
    header('Location: lojaview.php');
    exit;
}
} }
