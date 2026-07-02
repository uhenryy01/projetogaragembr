<?php
require_once __DIR__ . '/../models/Venda.php';
require_once __DIR__ . '/../models/Entrada.php';
require_once __DIR__ . '/../models/Produto.php';

class RelatorioController
{
    public function index(): void
    {
        $this->check();

        $vendaModel = new Venda();
        $vendas = $vendaModel->listarTodas();

        $entradaModel = new Entrada();
        $entradas = $entradaModel->listarTodas();

        $produtoModel = new Produto();
        $produtos = $produtoModel->listarTodos(true);
        $totalProdutos = count($produtos);

        $totalVendas = $vendaModel->totalGeral();
        $vendasMes = $vendaModel->quantidadeMes();
        $totalEntradas = 0;
        foreach ($entradas as $e) {
            $totalEntradas += $e['preco_custo'] * $e['quantidade'];
        }

        require_once __DIR__ . '/../views/relatorios.php';
    }

    private function check(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=form");
            exit;
        }
    }
}
