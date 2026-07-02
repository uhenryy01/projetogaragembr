<?php
session_start();
require_once __DIR__ . '/config/db.php';

$search = trim($_GET['search'] ?? '');

try {
    $pdo = Database::getConnection();
    if ($search !== '') {
        $term = '%' . $search . '%';
        $stmt = $pdo->prepare(
            "SELECT id, nome, marca, modelo, ano, cor, km, preco, preco_atual, imagem, descricao
             FROM carros
             WHERE ativo = 1 AND (nome LIKE :term OR marca LIKE :term OR modelo LIKE :term)
             ORDER BY id DESC"
        );
        $stmt->execute([':term' => $term]);
    } else {
        $stmt = $pdo->query(
            "SELECT id, nome, marca, modelo, ano, cor, km, preco, preco_atual, imagem, descricao FROM carros WHERE ativo = 1 ORDER BY id DESC"
        );
    }
    $carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $carros = [];
}

require_once __DIR__ . '/views/loja.php';
