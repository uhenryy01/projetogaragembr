<?php
require_once __DIR__ . '/../config/db.php';

$conn = Database::getConnection();

$conn->exec("CREATE TABLE IF NOT EXISTS entradas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  carro_id INT NOT NULL,
  quantidade INT NOT NULL DEFAULT 1,
  preco_custo DECIMAL(10,2) NOT NULL,
  fornecedor VARCHAR(200) DEFAULT NULL,
  data_entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  usuario_id INT NOT NULL,
  FOREIGN KEY (carro_id) REFERENCES carros(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$conn->exec("CREATE TABLE IF NOT EXISTS vendas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  carro_id INT NOT NULL,
  cliente_nome VARCHAR(150) NOT NULL,
  cliente_email VARCHAR(150) DEFAULT NULL,
  cliente_telefone VARCHAR(50) DEFAULT NULL,
  valor DECIMAL(10,2) NOT NULL,
  data_venda TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  usuario_id INT NOT NULL,
  FOREIGN KEY (carro_id) REFERENCES carros(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo "Tabelas criadas com sucesso.\n";
