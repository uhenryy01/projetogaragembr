<?php
require_once __DIR__ . '/../models/Categoria.php';
class CategoriaController
{
public function index(): void
{
$this->check();
$this->onlyAdmin();
$categoriaModel = new Categoria();
$categorias = $categoriaModel->listarTodas();
$editar = null;
if (isset($_GET['id'])) {
$editar = $categoriaModel->buscarPorId((int)$_GET['id']);
}
require_once __DIR__ . '/../views/categorias.php';
}
public function salvar(): void
{
$this->check();
$this->onlyAdmin();
$id = (int)($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
if ($nome === '') {
die("Nome inválido.");
}
$categoriaModel = new Categoria();
if ($id > 0) {
$categoriaModel->atualizar($id, $nome);
} else {
$categoriaModel->inserir($nome);
}
header("Location: index.php?controller=categoria&action=index");
exit;
}
public function toggle(): void
{
$this->check();
$this->onlyAdmin();
$id = (int)($_GET['id'] ?? 0);
$ativo = (int)($_GET['ativo'] ?? 1);
if ($id <= 0) die("ID inválido.");
$categoriaModel = new Categoria();
$categoriaModel->setAtivo($id, $ativo === 1);
header("Location: index.php?controller=categoria&action=index");
exit;
}
private function check(): void
{
if (!isset($_SESSION['usuario_id'])) {
header("Location: index.php?controller=auth&action=form");
exit;
} }
private function onlyAdmin(): void
{
if (($_SESSION['perfil'] ?? '') !== 'admin') {
    header('Location: loja.php');
    exit;
}
} }
