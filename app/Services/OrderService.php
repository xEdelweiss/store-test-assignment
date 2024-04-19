<?php

namespace App\Services;

use App\DTOs\Order\CartDto;
use App\DTOs\Order\CartItemDto;
use App\DTOs\Order\OrderFilterDto;
use App\DTOs\Product\StockRequirementsDto;
use App\DTOs\Product\ProductInStockDto;
use App\Events\OrderCreated;
use App\Events\OrderShipped;
use App\Models\Order;
use App\Models\User;
use App\Services\Order\OrderStatus;

class OrderService
{
    private int $itemsPerPage;

    public function __construct(
        private ProductService $productService,
        int                    $itemsPerPage = null,
    )
    {
        $this->itemsPerPage = $itemsPerPage ?? config('services.order.items_per_page');
    }

    public function makeOrder(User $user, CartDto $cart): Order
    {
        $this->ensureProductsExist($cart);

        $order = Order::create([
            'status' => OrderStatus::Created,
            'user_id' => $user->id,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->productId,
                'quantity' => $item->quantity,
            ]);
        }

        $this->dispatchOrderStatusChangeEvent($order);

        return $order;
    }

    public function getOrders(OrderFilterDto $filter, int $perPage = null): iterable
    {
        return Order::query()
            ->when($filter->userId, fn($query) => $query->whereUser($filter->userId))
            ->when($filter->status, fn($query) => $query->whereStatus($filter->status))
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage ?? $this->itemsPerPage);
    }

    public function isAvailableForShipment(Order $order): bool
    {
        try {
            $this->ensureOrderStatusChangeValid($order->status, OrderStatus::Shipped);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function markOrderShipped(Order $order, string $trackingNumber): void
    {
        $this->ensureOrderStatusChangeValid($order->status, OrderStatus::Shipped);

        $order->status = OrderStatus::Shipped;
        $order->tracking_number = $trackingNumber;
        $order->save();

        $this->dispatchOrderStatusChangeEvent($order);
    }

    private function ensureProductsExist(CartDto $cartDto): void
    {
        $requirements = new StockRequirementsDto(array_map(
            static fn(CartItemDto $item) => new ProductInStockDto($item->productId, $item->quantity),
            $cartDto->items
        ));

        $this->productService->checkStockQuantity($requirements);
    }

    private function ensureOrderStatusChangeValid(OrderStatus $previousStatus, OrderStatus $nextStatus): void
    {
        // here can be order validation logic
    }

    private function dispatchOrderStatusChangeEvent(Order $order): void
    {
        match ($order->status) {
            OrderStatus::Created => OrderCreated::dispatch($order),
            OrderStatus::Shipped => OrderShipped::dispatch($order),
            default => null
        };
    }
}
