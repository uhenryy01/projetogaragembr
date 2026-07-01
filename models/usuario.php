<?php
require_once __DIR__ . '/../config/db.php';
class Usuario
{
private $conn;
public function __construct()
{
$this->conn = Database::getConnection();
}
public function buscarPorEmail($email)
{
$sql = "SELECT usuarios.*, tipo AS perfil FROM usuarios WHERE email = :email LIMIT 1";
$stmt = $this->conn->prepare($sql);
$stmt->execute([':email' => $email]);
return $stmt->fetch();
}
public function cadastrar($nome, $email, $senha_hash, $perfil = 'cliente')
    {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo)
                VALUES (:nome, :email, :senha, :tipo)";
       
        $stmt = $this->conn->prepare($sql);
       
        return $stmt->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':senha' => $senha_hash,
            ':tipo'  => $perfil
        ]);
    }








}


