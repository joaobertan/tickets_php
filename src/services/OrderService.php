<?php
namespace App\services;

use App\repository\PDO\OrderRepositoryPDO;
use App\repository\PDO\OrderItemRepositoryPDO;
use App\services\TicketService;
use App\services\ReservedService;

class OrderService {
    private $orderRepo;
    private $itemRepo;
    private $ticketService;
    private $reservedService;

    public function __construct() {
        $this->orderRepo = new OrderRepositoryPDO();
        $this->itemRepo = new OrderItemRepositoryPDO();
        $this->ticketService = new TicketService();
        $this->reservedService = new ReservedService();
    }

    public function create(string $userId, string $ticketId, int $amount): bool {
        $currentTicketAmount = $this->ticketService->findById($ticketId)->amount;
        $validReservationsCount = $this->reservedService->countValidReservations($ticketId);
        
        $realDisponibleAmount = $currentTicketAmount - $validReservationsCount + 1;

        if ($realDisponibleAmount < $amount) {
            echo "Quantidade disponível: $realDisponibleAmount";
            return false; 
        }

        $validReservation = $this->reservedService->findValidReservation($ticketId, $userId);
        if (!$validReservation) {
            echo "Sua reserva expirou.";
            return false; 
        } 

        $orderId = $this->orderRepo->create($userId);
        
        if (!$orderId) return false;
        $orderItem = $this->itemRepo->create($orderId, $ticketId, $amount);

        if (!$orderItem) return false;
        $ticketUpdated = $this->ticketService->decreaseAmount($ticketId, $amount);

        if (!$ticketUpdated) {
            $this->itemRepo->delete($orderItem->id);
            $this->orderRepo->delete($orderId);
            return false;
        }

        $this->reservedService->deleteByUserId($userId);
        return true;
    }

    public function findByUserId(string $userId): array {
        return $orders = $this->orderRepo->findByUserId($userId);
    }
}
