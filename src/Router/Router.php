<?php
namespace App\Router;

use App\controllers\AuthController;
use App\controllers\TicketController;
use App\services\SessionService;

SessionService::init();

$path = $_SERVER['PATH_INFO'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

switch ($path) {
    case '/':
        header('Location: /dashboard');
        exit;

    case '/login':
		if (SessionService::loggedUser()) {
            header('Location: /dashboard');
            exit;
        } 

        require __DIR__ . '/../../public/login.php';
        break;

    case '/dashboard':
        if (!SessionService::loggedUser()) {
            header('Location: /login');
            exit;
        }

        require __DIR__ . '/../../public/dashboard.php';
        break;

    case '/profile':
        if (!SessionService::loggedUser()) {
            header('Location: /login');
            exit;
        }

        require __DIR__ . '/../../public/profile.php';
        break;

    case '/create_ticket':
        if (!SessionService::loggedUser()) {
            header('Location: /login');
            exit;
        }

        if (SessionService::getRole() !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        require __DIR__ . '/../../public/create_ticket.php';
        break;

    case '/ticket_buy':
        if (!SessionService::loggedUser()) {
            header('Location: /login');
            exit;
        }

        if (SessionService::getRole() !== 'client') {
            header('Location: /dashboard');
            exit;
        }

        require __DIR__ . '/../../public/ticket_buy.php';
        break;

    case '/my_tickets':
        if (!SessionService::loggedUser()) {
            header('Location: /login');
            exit;
        }

        if (SessionService::getRole() !== 'client') {
            header('Location: /dashboard');
            exit;
        }

        require __DIR__ . '/../../public/my_tickets.php';
        break;

    case '/confirm_buy':
        if (!SessionService::loggedUser()) {
            header('Location: /login');
            exit;
        }

        if (SessionService::getRole() !== 'client') {
            header('Location: /dashboard');
            exit;
        }

        require __DIR__ . '/../../public/ticket_buy.php';
        break;

    case '/ticket_edit':
        if (!SessionService::loggedUser()) {
            header('Location: /login');
            exit;
        }

        if (SessionService::getRole() !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        require __DIR__ . '/../../public/ticket_edit.php';
        break;
    
    case '/ticket_delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new TicketController();
            $controller->delete($_POST['id']);
            header('Location: /dashboard');
            exit;
        }
        break;

    case '/logout':
        if ($method === 'POST') {
            SessionService::logout();
        }
        header('Location: /login');
        exit;

    case '/register':
        if (SessionService::loggedUser()) {
            header('Location: /dashboard');
            exit;
        }

        require __DIR__ . '/../../public/register.php';
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada.";
}
