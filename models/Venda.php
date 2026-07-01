<?php
require_once __DIR__ . '/../config/db.php';

class Venda
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function listarTodas(): array
    {
        $sql = "SELECT v.*, c.nome AS carro_nome, c.marca, c.modelo, u.nome AS usuario_nome
                FROM vendas v
                LEFT JOIN carros c ON c.id = v.carro_id
                LEFT JOIN usuarios u ON u.id = v.usuario_id
                ORDER BY v.data_venda DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM vendas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function inserir(int $carroId, string $clienteNome, ?string $clienteEmail, ?string $clienteTelefone, float $valor, int $usuarioId): int
    {
        $stmt = $this->conn->prepare("INSERT INTO vendas (carro_id, cliente_nome, cliente_email, cliente_telefone, valor, usuario_id)
                                      VALUES (:carro_id, :cliente_nome, :cliente_email, :cliente_telefone, :valor, :usuario_id)");
        $stmt->execute([
            ':carro_id'         => $carroId,
            ':cliente_nome'     => $clienteNome,
            ':cliente_email'    => $clienteEmail,
            ':cliente_telefone' => $clienteTelefone,
            ':valor'            => $valor,
            ':usuario_id'       => $usuarioId,
        ]);
        return (int)$this->conn->lastInsertId();
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM vendas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function totalMes(): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) FROM vendas WHERE MONTH(data_venda) = MONTH(CURRENT_DATE) AND YEAR(data_venda) = YEAR(CURRENT_DATE)";
        return (float)$this->conn->query($sql)->fetchColumn();
    }

    public function totalGeral(): float
    {
        return (float)$this->conn->query("SELECT COALESCE(SUM(valor), 0) FROM vendas")->fetchColumn();
    }

    public function quantidadeMes(): int
    {
        $sql = "SELECT COUNT(*) FROM vendas WHERE MONTH(data_venda) = MONTH(CURRENT_DATE) AND YEAR(data_venda) = YEAR(CURRENT_DATE)";
        return (int)$this->conn->query($sql)->fetchColumn();
    }
}
