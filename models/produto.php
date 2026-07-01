<?php
require_once __DIR__ . '/../config/db.php';
class Produto
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function listarTodos(bool $somenteAtivos = false): array
    {
        $sql = "SELECT * FROM carros";
        if ($somenteAtivos) {
            $sql .= " WHERE ativo = 1";
        }
        $sql .= " ORDER BY id DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM carros WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    public function inserir(string $nome, string $marca, string $modelo, int $ano, string $cor, int $km, float $preco, ?float $precoAtual, ?string $descricao, int $adminId): int
    {
        if ($precoAtual === null) {
            $precoAtual = $preco;
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO carros (nome, marca, modelo, ano, cor, km, preco, preco_inicial, preco_atual, descricao, ativo, admin_id)
            VALUES (:nome, :marca, :modelo, :ano, :cor, :km, :preco, :preco_inicial, :preco_atual, :descricao, 1, :admin_id)"
        );
        $stmt->execute([
            ':nome' => $nome,
            ':marca' => $marca,
            ':modelo' => $modelo,
            ':ano' => $ano,
            ':cor' => $cor,
            ':km' => $km,
            ':preco' => $preco,
            ':preco_inicial' => $preco,
            ':preco_atual' => $precoAtual,
            ':descricao' => $descricao,
            ':admin_id' => $adminId,
        ]);
        return (int)$this->conn->lastInsertId();
    }

    public function atualizar(int $id, string $nome, string $marca, string $modelo, int $ano, string $cor, int $km, float $preco, ?float $precoAtual, ?string $descricao): void
    {
        $stmt = $this->conn->prepare(
            "UPDATE carros SET nome = :nome, marca = :marca, modelo = :modelo, ano = :ano, cor = :cor, km = :km, preco = :preco, preco_atual = :preco_atual, descricao = :descricao WHERE id = :id"
        );
        $stmt->execute([
            ':id' => $id,
            ':nome' => $nome,
            ':marca' => $marca,
            ':modelo' => $modelo,
            ':ano' => $ano,
            ':cor' => $cor,
            ':km' => $km,
            ':preco' => $preco,
            ':preco_atual' => $precoAtual,
            ':descricao' => $descricao,
        ]);
    }

    public function setAtivo(int $id, bool $ativo): void
    {
        $stmt = $this->conn->prepare("UPDATE carros SET ativo = :ativo WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':ativo' => $ativo ? 1 : 0,
        ]);
    }

    public function deletar(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM carros WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
