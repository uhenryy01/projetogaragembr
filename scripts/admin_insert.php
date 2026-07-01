<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../models/produto.php';

try {
    $db = Database::getConnection();

    $usuarioModel = new Usuario();
    $email = 'adm@gmail.com';
    $admin = $usuarioModel->buscarPorEmail($email);
    if (!$admin) {
        $senha_hash = password_hash('12345', PASSWORD_DEFAULT);
        $criado = $usuarioModel->cadastrar('Administrador', $email, $senha_hash, 'admin');
        if (!$criado) {
            throw new Exception('Falha ao cadastrar admin.');
        }
        $admin = $usuarioModel->buscarPorEmail($email);
        if (!$admin) throw new Exception('Admin ainda não encontrado após cadastro.');
    }

    $adminId = (int)$admin['id'];

    $produtoModel = new Produto();
    $novoId = $produtoModel->inserir(
        'Carro de Teste via Script',
        'MarcaTest',
        'ModeloTest',
        2020,
        'Preto',
        100,
        12345.67,
        null,
        'Descrição de teste',
        $adminId
    );

    $uploadsDir = __DIR__ . '/../public/uploads/carros';
    if (!is_dir($uploadsDir)) {
        if (!mkdir($uploadsDir, 0777, true)) {
            throw new Exception('Falha ao criar pasta de uploads: ' . $uploadsDir);
        }
    }

    $dest = $uploadsDir . '/' . $novoId . '.jpg';

    $jpegBase64 = '/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxAQEBUQEA8QEA8PEA8QEA8QEA8QEA8QFREWFhURFRUYHSggGBolGxUVITEhJSkrLi4uFx8zODMsNygtLisBCgoKDg0OGxAQGy0lICUtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAKABJAMBIgACEQEDEQH/xAAbAAEAAgMBAQAAAAAAAAAAAAAABAYBAwUHAv/EADcQAAIBAwMCBAQEBwAAAAAAAAECAwAEEQUSITEGE0EiUXGBkRShscHR8AcVMkJS4fFi/8QAGgEBAQEBAQEBAAAAAAAAAAAAAAECBAMFBv/EACIRAQEBAAICAgEFAAAAAAAAAAABAhEDIRIxQVETImFxgf/aAAwDAQACEQMRAD8A+6iiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigD/2Q==';

    $jpegData = base64_decode($jpegBase64);
    file_put_contents($dest, $jpegData);

    $stmt = $db->prepare('SELECT id, nome, preco, preco_inicial, preco_atual, admin_id FROM carros WHERE id = :id');
    $stmt->execute([':id' => $novoId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) throw new Exception('Não encontrou o carro inserido no DB.');

} catch (Exception $e) {
    echo 'ERRO: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

exit(0);
