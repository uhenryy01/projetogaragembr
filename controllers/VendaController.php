<?php
require_once __DIR__ . '/../models/Venda.php';
require_once __DIR__ . '/../models/Produto.php';

class VendaController
{
    public function index(): void
    {
        $this->check();

        $vendaModel = new Venda();
        $vendas = $vendaModel->listarTodas();

        $produtoModel = new Produto();
        $carros = $produtoModel->listarTodos(true);

        require_once __DIR__ . '/../views/vendas.php';
    }

    public function salvar(): void
    {
        $this->check();

        $carroId = (int)($_POST['carro_id'] ?? 0);
        $clienteNome = trim($_POST['cliente_nome'] ?? '');
        $clienteEmail = trim($_POST['cliente_email'] ?? '');
        $clienteTelefone = trim($_POST['cliente_telefone'] ?? '');
        $valor = $this->parseDecimal((string)($_POST['valor'] ?? '0'));

        if ($carroId <= 0 || $clienteNome === '' || $valor <= 0) {
            die("Dados inválidos.");
        }

        $clienteEmail = $clienteEmail === '' ? null : $clienteEmail;
        $clienteTelefone = $clienteTelefone === '' ? null : $clienteTelefone;

        $vendaModel = new Venda();
        $vendaModel->inserir($carroId, $clienteNome, $clienteEmail, $clienteTelefone, $valor, (int)$_SESSION['usuario_id']);

        header("Location: index.php?controller=venda&action=index");
        exit;
    }

    public function deletar(): void
    {
        $this->check();
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) die("ID inválido.");

        $vendaModel = new Venda();
        $vendaModel->deletar($id);

        header("Location: index.php?controller=venda&action=index");
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
