<?php

namespace App\Services;

use App\DTOs\Order\OrderFilterDto;
use App\Models\User;

class UserService
{
    private int $historyItemsPerPage;

    public function __construct(
        readonly private OrderService $orderService,
        int $historyItemsPerPage = null
    ) {
        $this->historyItemsPerPage = $historyItemsPerPage ?? config('services.user.order_history.items_per_page');
    }

    public function getOrdersHistory(User $user, int $perPage = null): iterable
    {
        return $this->orderService->getOrders(new OrderFilterDto($user->id), $perPage ?? $this->historyItemsPerPage);
    }
}
