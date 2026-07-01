<?php
require_once __DIR__ . '/../config/db.php';

class ContatoController
{
    public function index(): void
    {
        $this->check();
        $this->onlyAdmin();

        $pdo = Database::getConnection();
        $this->ensureContatoSchema($pdo);

        $stmt = $pdo->query(
            "SELECT
                c.id,
                c.carro_id,
                c.nome,
                c.email,
                c.telefone,
                c.mensagem,
                c.resposta,
                c.respondido_em,
                c.criado_em,
                CONCAT_WS(' ', ca.nome, ca.marca, ca.modelo) AS carro_nome
            FROM contatos c
            LEFT JOIN carros ca ON ca.id = c.carro_id
            ORDER BY c.criado_em DESC"
        );
        $contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/contatos.php';
    }

    public function reply(): void
    {
        $this->thread();
    }

    public function thread(): void
    {
        $this->check();
        $this->onlyAdmin();

        $pdo = Database::getConnection();
        $this->ensureContatoSchema($pdo);
        $this->ensureContatoMessagesSchema($pdo);
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die('ID inválido.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $texto = trim($_POST['texto'] ?? '');
            if ($texto === '') {
                die('Mensagem não pode ficar em branco.');
            }

            $stmt = $pdo->prepare('INSERT INTO contato_mensagens (contato_id, sender, texto) VALUES (:contato_id, :sender, :texto)');
            $stmt->execute([
                ':contato_id' => $id,
                ':sender' => 'admin',
                ':texto' => $texto,
            ]);

            $stmt = $pdo->prepare('UPDATE contatos SET resposta = :resposta, respondido_em = NOW() WHERE id = :id');
            $stmt->execute([':resposta' => $texto, ':id' => $id]);

            header('Location: index.php?controller=contato&action=thread&id=' . $id);
            exit;
        }

        $stmt = $pdo->prepare('SELECT * FROM contatos WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $contato = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$contato) {
            die('Mensagem não encontrada.');
        }

        $stmt = $pdo->prepare('SELECT sender, texto, criado_em FROM contato_mensagens WHERE contato_id = :id ORDER BY criado_em ASC');
        $stmt->execute([':id' => $id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($messages) && !empty($contato['mensagem'])) {
            $messages[] = [
                'sender' => 'client',
                'texto' => $contato['mensagem'],
                'criado_em' => $contato['criado_em'],
            ];
        }

        require_once __DIR__ . '/../views/contato_reply.php';
    }

    private function check(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?controller=auth&action=form');
            exit;
        }
    }

    private function ensureContatoSchema(PDO $pdo): void
    {
        $pdo->exec("CREATE TABLE IF NOT EXISTS contatos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            carro_id INT NULL,
            nome VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            telefone VARCHAR(80) NOT NULL,
            mensagem TEXT NOT NULL,
            resposta TEXT NULL,
            respondido_em TIMESTAMP NULL DEFAULT NULL,
            criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $existing = [];
        $stmt = $pdo->query("SHOW COLUMNS FROM contatos");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $existing[$row['Field']] = true;
        }

        if (!isset($existing['resposta'])) {
            $pdo->exec("ALTER TABLE contatos ADD COLUMN resposta TEXT NULL AFTER mensagem");
        }
        if (!isset($existing['respondido_em'])) {
            $pdo->exec("ALTER TABLE contatos ADD COLUMN respondido_em TIMESTAMP NULL DEFAULT NULL AFTER resposta");
        }
    }

    private function ensureContatoMessagesSchema(PDO $pdo): void
    {
        $pdo->exec("CREATE TABLE IF NOT EXISTS contato_mensagens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            contato_id INT NOT NULL,
            sender VARCHAR(20) NOT NULL,
            texto TEXT NOT NULL,
            criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            INDEX idx_contato_id (contato_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    private function onlyAdmin(): void
    {
        if (($_SESSION['perfil'] ?? '') !== 'admin') {
            header('Location: loja.php');
            exit;
        }
    }
}
