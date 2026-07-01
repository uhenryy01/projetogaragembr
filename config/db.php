<?php
class Database
{
private static $conn = null;
public static function getConnection()
{
if (self::$conn === null) {
try {
self::$conn = new PDO(
"mysql:host=localhost;dbname=leilao_de_carros;charset=utf8mb4",
"root",
"",
[
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]
);
} catch (PDOException $e) {
die("Erro ao conectar ao banco de dados.");
}
}
return self::$conn;
}
}
