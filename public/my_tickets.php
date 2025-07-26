<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\services\SessionService;
use App\services\OrderService;

$userId = $_SESSION['id_user'] ?? null;
$orderService = new OrderService();
$tickets = $orderService->findByUserId($userId);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Ingressos</title>
    <link rel="stylesheet" href="css/my_tickets.css">
</head>
<body>
    <div class="my-tickets-container"> <h1>Meus Ingressos Comprados</h1>

        <?php if (empty($tickets)): ?>
            <p class="no-tickets-message">Você ainda não comprou nenhum ingresso.</p> <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Data do Evento</th>
                        <th>Quantidade</th>
                        <th>Valor Unitário</th>
                        <th>Data da Compra</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?= htmlspecialchars($ticket['title']) ?></td>
                            <td><?= date('d/m/Y', $ticket['event_date']) ?></td>
                            <td><?= htmlspecialchars($ticket['amount']) ?></td>
                            <td>R$ <?= number_format($ticket['value'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y H:i', $ticket['purchase_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <p><a href="/dashboard" class="back-link">Voltar</a></p> </div> </body>
</html>
