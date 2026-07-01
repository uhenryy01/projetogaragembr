<?php
require_once __DIR__ . '/../models/Produto.php';
class ProdutoController
{
    public function index(): void
    {
        $this->check();
        $this->onlyAdmin();

        $produtoModel = new Produto();
        $produtos = $produtoModel->listarTodos(false);
        $editar = null;
        if (isset($_GET['id'])) {
            $editar = $produtoModel->buscarPorId((int)$_GET['id']);
        }

        require_once __DIR__ . '/../views/produtos.php';
    }

    public function salvar(): void
    {
        $this->check();
        $this->onlyAdmin();

        $id = (int)($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $marca = trim($_POST['marca'] ?? '');
        $modelo = trim($_POST['modelo'] ?? '');
        $ano = (int)($_POST['ano'] ?? 0);
        $cor = trim($_POST['cor'] ?? '');
        $km = (int)($_POST['km'] ?? 0);
        $preco = $this->parseDecimal((string)($_POST['preco'] ?? '0'));
        $precoAtual = trim($_POST['preco_atual'] ?? '') !== '' ? $this->parseDecimal((string)$_POST['preco_atual']) : null;
        $descricao = trim($_POST['descricao'] ?? '');
        $descricao = $descricao === '' ? null : $descricao;

        if ($nome === '' || $marca === '' || $modelo === '' || $preco <= 0) {
            die("Dados inválidos.");
        }

        if ($precoAtual === null) {
            $precoAtual = $preco;
        }

        $produtoModel = new Produto();
        if ($id > 0) {
            $produtoModel->atualizar($id, $nome, $marca, $modelo, $ano, $cor, $km, $preco, $precoAtual, $descricao);
            $this->salvarImagemDoProduto($id);
        } else {
            $adminId = (int)($_SESSION['usuario_id'] ?? 0);
            if ($adminId <= 0 && !empty($_SESSION['usuario_email'])) {
                require_once __DIR__ . '/../models/Usuario.php';
                $usuarioModel = new Usuario();
                $admin = $usuarioModel->buscarPorEmail($_SESSION['usuario_email']);
                $adminId = $admin['id'] ?? 0;
            }

            if ($adminId <= 0) {
                die("Admin inválido.");
            }
            $novoId = $produtoModel->inserir($nome, $marca, $modelo, $ano, $cor, $km, $preco, $precoAtual, $descricao, $adminId);
            $this->salvarImagemDoProduto($novoId);
        }

        header("Location: index.php?controller=produto&action=index");
        exit;
    }

    public function toggle(): void
    {
        $this->check();
        $this->onlyAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $ativo = (int)($_GET['ativo'] ?? 1);
        if ($id <= 0) die("ID inválido.");
        $produtoModel = new Produto();
        $produtoModel->setAtivo($id, $ativo === 1);
        header("Location: index.php?controller=produto&action=index");
        exit;
    }

    private function normalizeDecimal(string $value): string
    {
        $value = trim(str_replace(' ', '', $value));
        if ($value === '') {
            return '0';
        }

        if (strpos($value, ',') !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }

        return $value;
    }

    private function parseDecimal(string $value): float
    {
        return (float)$this->normalizeDecimal($value);
    }

    public function deletar(): void
    {
        $this->check();
        $this->onlyAdmin();
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) die("ID inválido.");

        $produtoModel = new Produto();
        $produto = $produtoModel->buscarPorId($id);

        if (!$produto) die("Produto não encontrado.");

        $this->deletarImagemDoProduto($id);
        $produtoModel->deletar($id);

        header("Location: index.php?controller=produto&action=index");
        exit;
    }

    private function salvarImagemDoProduto(int $produtoId): void
    {
        if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
            return;
        }
        if (($_FILES['imagem']['size'] ?? 0) > 2 * 1024 * 1024) {
            return;
        }
        $tmp = $_FILES['imagem']['tmp_name'];
        $mime = mime_content_type($tmp);
        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => null
        };
        if ($ext === null) return;

        $destDir = __DIR__ . '/../public/uploads/carros/';
        if (!is_dir($destDir)) {
            mkdir($destDir, 0777, true);
        }
        foreach (['jpg','png','webp'] as $e) {
            $old = $destDir . $produtoId . '.' . $e;
            if (file_exists($old)) unlink($old);
        }
        $dest = $destDir . $produtoId . '.' . $ext;
        move_uploaded_file($tmp, $dest);
    }

    private function deletarImagemDoProduto(int $produtoId): void
    {
        $destDir = __DIR__ . '/../public/uploads/carros/';
        foreach (['jpg','png','webp'] as $ext) {
            $arquivo = $destDir . $produtoId . '.' . $ext;
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
        }
    }

    private function check(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=form");
            exit;
        }
    }

    private function onlyAdmin(): void
    {
        if (($_SESSION['perfil'] ?? '') !== 'admin') {
            header('Location: loja.php');
            exit;
        }
    }
}
