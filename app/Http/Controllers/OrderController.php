<?php

namespace App\Http\Controllers;

use App\Exceptions\Product\NotEnoughInStockException;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\StockMismatchItemResource;
use App\Models\Order;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->makeOrder(
                $request->user(),
                $request->toDto(),
            );
        } catch (NotEnoughInStockException $exception) {
            return StockMismatchItemResource::collection($exception->getMismatchItems())
                ->additional([
                    'message' => 'Not enough in stock',
                ])
                ->response()
                ->setStatusCode(Response::HTTP_CONFLICT);
        }

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }
}
