<?php
session_start();
require_once __DIR__ . '/config/db.php';
Database::getConnection();


$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'form';

switch ($controller) {
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $c = new AuthController();
        break;

    case 'produto':
        require_once __DIR__ . '/controllers/ProdutoController.php';
        $c = new ProdutoController();
        break;

    case 'entrada':
        require_once __DIR__ . '/controllers/EntradaController.php';
        $c = new EntradaController();
        break;

    case 'venda':
        require_once __DIR__ . '/controllers/VendaController.php';
        $c = new VendaController();
        break;

    case 'relatorio':
        require_once __DIR__ . '/controllers/Relatorio_Controller.php';
        $c = new RelatorioController();
        break;

    case 'usuario':
        require_once __DIR__ . '/controllers/UsuarioController.php';
        $c = new UsuarioController();
        break;
     case 'categoria':
         require_once __DIR__ . '/controllers/CategoriaController.php';
         $c = new CategoriaController();
        break;

    case 'contato':
        require_once __DIR__ . '/controllers/ContatoController.php';
        $c = new ContatoController();
        break;

    case 'whatsapp':
        require_once __DIR__ . '/controllers/WhatsAppController.php';
        $c = new WhatsAppController();
        break;

    default:
        die("Controller inválido.");
}

if (!method_exists($c, $action)) {
    die("Ação inválida.");
}


$c->$action();
