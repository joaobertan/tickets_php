<?php
require __DIR__ . '/../vendor/autoload.php';

use App\services\SessionService;
use App\services\TicketService;
use App\services\ReservedService;

if (!SessionService::loggedUser()) {
    header('Location: /login');
    exit;
}

$userId = SessionService::getId();
$role = SessionService::getRole();
$tickets = [];

$ticketService = new TicketService();
$reservedService = new ReservedService(); 

if ($role === 'admin') {
    $tickets = $ticketService->findByUserId($userId);    
} else if ($role === 'client') {
    $tickets = $ticketService->findAvailable();

    // Essa função serve pra deletar todas as reservas do usuário, quando ele acessa a tela de dashboard, 
	// pois se ele ta no dashboard ele não ta comprando nada, logo não precisa mais das reservas.
    $reservedService->deleteByUserId($userId);
}

function formatDate(int $timestamp): string {
    return date('d/m/Y', $timestamp);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="header-dashboard">
        <h1>DASHBOARD</h1>
        <div class="top-actions">
            <p class="welcome-message">Bem-vindo ao painel de controle!</p>
            <a href="/profile" class="button">Minha conta</a>
            <form method="POST" action="/logout">
                <button type="submit" class="logout-button">Sair</button>
            </form>
        </div>
    </div>

    <?php if ($role === 'admin'): ?>
        <a href="/create_ticket" class="button">Criar Ticket</a>

        <h2>Meus Tickets</h2>
        <?php if (empty($tickets)): ?>
            <p class="no-tickets-message">Nenhum ticket criado ainda.</p>
        <?php else: ?>
            <div class="ticket-grid">
                <?php foreach ($tickets as $ticket): ?>
                    <div class="card">
                        <div>
                            <h3><?= htmlspecialchars($ticket['title']) ?></h3>
                            <p><?= htmlspecialchars(mb_strimwidth($ticket['description'], 0, 100, '...')) ?></p>
                            <p><strong>Evento:</strong> <?= formatDate((int)$ticket['event_date']) ?></p>
                            <p><strong>Criado em:</strong> <?= formatDate((int)$ticket['created_at']) ?></p>

                            <?php if ($ticket['deleted_at']): ?>
                                <p class="status-removed"><strong>Removido em:</strong> <?= formatDate((int)$ticket['deleted_at']) ?></p>
                            <?php endif; ?>

                            <?php if ((int)$ticket['amount'] === 0): ?>
                                <p class="status-sold-out"><strong>Esgotado</strong></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <form method="GET" action="/ticket_edit">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($ticket['id']) ?>">
                                <button type="submit">Editar</button>
                            </form>

                            <?php if (is_null($ticket['deleted_at'])): ?>
                                <form method="POST" action="/ticket_delete" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($ticket['id']) ?>">
                                    <button type="submit" class="delete-button">Excluir</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($role === 'client'): ?>
        <a href="/my_tickets" class="button">Vizualizar minhas compras</a>
        <h2>Tickets disponíveis para compra</h2>
        <?php if (empty($tickets)): ?>
            <p class="no-tickets-message">Nenhum ticket disponível no momento.</p>
        <?php else: ?>
            <div class="ticket-grid">
                <?php foreach ($tickets as $ticket): ?>
                    <div class="card">
                        <div>
                            <h3><?= htmlspecialchars($ticket['title']) ?></h3>
                            <p><?= htmlspecialchars(mb_strimwidth($ticket['description'], 0, 100, '...')) ?></p>
                            <p><strong>Evento:</strong> <?= formatDate((int)$ticket['event_date']) ?></p>
                            <p><strong>Preço:</strong> R$ <?= number_format($ticket['value'], 2, ',', '.') ?></p>
                            <p><strong>Estoque disponível:</strong> <?= (int)$ticket['available_stock'] ?></p>
                        </div>
                        <div class="card-actions">
                            <form method="POST" action="/ticket_buy">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($ticket['id']) ?>">
                                <button type="submit">Comprar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
