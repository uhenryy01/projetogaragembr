<?php
session_start();
require_once __DIR__ . '/../config/db.php';

class WhatsAppController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function config(): void
    {
        $this->checkAdmin();

        $saved = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero = preg_replace('/[^0-9]/', '', trim($_POST['numero'] ?? ''));
            $stmt = $this->pdo->prepare("UPDATE whatsapp_config SET numero = :numero WHERE id = 1");
            $stmt->execute([':numero' => $numero]);
            $saved = true;
        }

        $stmt = $this->pdo->query("SELECT numero FROM whatsapp_config WHERE id = 1");
        $config = $stmt->fetch(PDO::FETCH_ASSOC);
        $numero = $config ? $config['numero'] : '';

        require_once __DIR__ . '/../views/whatsapp_config.php';
    }

    private function checkAdmin(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?controller=auth&action=form');
            exit;
        }
        if (($_SESSION['perfil'] ?? '') !== 'admin') {
            header('Location: lojaview.php');
            exit;
        }
    }
}
