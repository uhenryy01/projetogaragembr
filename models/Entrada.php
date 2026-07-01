<?php
require_once __DIR__ . '/../config/db.php';

class Entrada
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function listarTodas(): array
    {
        $sql = "SELECT e.*, c.nome AS carro_nome, c.marca, c.modelo, u.nome AS usuario_nome
                FROM entradas e
                LEFT JOIN carros c ON c.id = e.carro_id
                LEFT JOIN usuarios u ON u.id = e.usuario_id
                ORDER BY e.data_entrada DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM entradas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function inserir(int $carroId, int $quantidade, float $precoCusto, ?string $fornecedor, int $usuarioId): int
    {
        $stmt = $this->conn->prepare("INSERT INTO entradas (carro_id, quantidade, preco_custo, fornecedor, usuario_id)
                                      VALUES (:carro_id, :quantidade, :preco_custo, :fornecedor, :usuario_id)");
        $stmt->execute([
            ':carro_id'    => $carroId,
            ':quantidade'  => $quantidade,
            ':preco_custo' => $precoCusto,
            ':fornecedor'  => $fornecedor,
            ':usuario_id'  => $usuarioId,
        ]);
        return (int)$this->conn->lastInsertId();
    }

    public function atualizar(int $id, int $carroId, int $quantidade, float $precoCusto, ?string $fornecedor): void
    {
        $stmt = $this->conn->prepare("UPDATE entradas SET carro_id = :carro_id, quantidade = :quantidade, preco_custo = :preco_custo, fornecedor = :fornecedor WHERE id = :id");
        $stmt->execute([
            ':id'          => $id,
            ':carro_id'    => $carroId,
            ':quantidade'  => $quantidade,
            ':preco_custo' => $precoCusto,
            ':fornecedor'  => $fornecedor,
        ]);
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM entradas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
