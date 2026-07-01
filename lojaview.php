<<<<<<< HEAD
<?php
session_start();
require_once __DIR__ . '/config/db.php';

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query(
        "SELECT id, nome, marca, modelo, ano, cor, km, preco, preco_atual, imagem, descricao FROM carros WHERE ativo = 1 ORDER BY id DESC"
    );
    $carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $carros = [];
}

require_once __DIR__ . '/views/loja.php';
=======
<?php
session_start();
require_once __DIR__ . '/config/db.php';

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query(
        "SELECT id, nome, marca, modelo, ano, cor, km, preco, preco_atual, imagem, descricao FROM carros WHERE ativo = 1 ORDER BY id DESC"
    );
    $carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $carros = [];
}

require_once __DIR__ . '/views/loja.php';
>>>>>>> 95062992a067d7f41dc952fb38d27af398c80887
