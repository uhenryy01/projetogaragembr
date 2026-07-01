<?php
require_once __DIR__ . '/../config/db.php';
class Categoria
{
private PDO $conn;
public function __construct()
{
$this->conn = Database::getConnection();
}
public function listarTodas(): array
{
$sql = "SELECT id, nome, ativo FROM categoria ORDER BY nome";
return $this->conn->query($sql)->fetchAll();
}
public function listarAtivas(): array
{
$sql = "SELECT id, nome FROM categoria WHERE ativo = 1 ORDER BY nome";
return $this->conn->query($sql)->fetchAll();
}
public function buscarPorId(int $id): ?array
{
$stmt = $this->conn->prepare("SELECT id, nome, ativo FROM categoria WHERE id = :id");
$stmt->execute([':id' => $id]);
$r = $stmt->fetch();
return $r ?: null;
}
public function inserir(string $nome): int
{
$stmt = $this->conn->prepare("
INSERT INTO categoria (nome, ativo)
VALUES (:nome, 1)
");
$stmt->execute([':nome' => $nome]);
return (int)$this->conn->lastInsertId();
}
public function atualizar(int $id, string $nome): void
{
$stmt = $this->conn->prepare("
UPDATE categoria
SET nome = :nome
WHERE id = :id
");
$stmt->execute([
    ':id' => $id,
':nome' => $nome
]);
}
public function setAtivo(int $id, bool $ativo): void
{
$stmt = $this->conn->prepare("
UPDATE categoria
SET ativo = :ativo
WHERE id = :id
");
$stmt->execute([
':id' => $id,
':ativo' => $ativo ? 1 : 0
]);
}
}