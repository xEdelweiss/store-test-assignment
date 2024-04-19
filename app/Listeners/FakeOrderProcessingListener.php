<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Exceptions\Order\InvalidOrderStatus;
use App\Services\OrderService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Considering that shipping is done by robots, we can fake it
 */
class FakeOrderProcessingListener implements ShouldQueue
{
    public function __construct(
        // readonly private ShippingService $shippingService,
        private OrderService $orderService,
    ) {}

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        if (!$this->orderService->isAvailableForShipment($event->order)) {
            throw new InvalidOrderStatus($event->order);
        }

        // $trackingNumber = $this->shippingService->shipOrder($event->order);
        $trackingNumber = '123456789';
        $this->orderService->markOrderShipped($event->order, $trackingNumber);
    }
}
