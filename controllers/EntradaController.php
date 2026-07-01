<?php
require_once __DIR__ . '/../models/Entrada.php';
require_once __DIR__ . '/../models/Produto.php';

class EntradaController
{
    public function index(): void
    {
        $this->check();

        $entradaModel = new Entrada();
        $entradas = $entradaModel->listarTodas();

        $produtoModel = new Produto();
        $carros = $produtoModel->listarTodos(true);

        require_once __DIR__ . '/../views/entradas.php';
    }

    public function salvar(): void
    {
        $this->check();

        $carroId = (int)($_POST['carro_id'] ?? 0);
        $quantidade = (int)($_POST['quantidade'] ?? 1);
        $precoCusto = $this->parseDecimal((string)($_POST['preco_custo'] ?? '0'));
        $fornecedor = trim($_POST['fornecedor'] ?? '');
        $fornecedor = $fornecedor === '' ? null : $fornecedor;

        if ($carroId <= 0 || $quantidade <= 0 || $precoCusto <= 0) {
            die("Dados inválidos.");
        }

        $entradaModel = new Entrada();
        $entradaModel->inserir($carroId, $quantidade, $precoCusto, $fornecedor, (int)$_SESSION['usuario_id']);

        header("Location: index.php?controller=entrada&action=index");
        exit;
    }

    public function deletar(): void
    {
        $this->check();
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) die("ID inválido.");

        $entradaModel = new Entrada();
        $entradaModel->deletar($id);

        header("Location: index.php?controller=entrada&action=index");
        exit;
    }

    private function parseDecimal(string $value): float
    {
        $value = trim(str_replace(' ', '', $value));
        if ($value === '') return 0;
        if (strpos($value, ',') !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }
        return (float)$value;
    }

    private function check(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=form");
            exit;
        }
    }
}
