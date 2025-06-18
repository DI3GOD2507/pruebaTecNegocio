<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->orderService->getAllOrders());
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);
        if (!$order) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }
        return response()->json($order);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'CustomerId' => 'required|integer|exists:Customers,Id',
            'OrderDate' => 'required|date',
            'Status' => 'required|string|max:50',
            'TotalAmount' => 'required|numeric',
            'Notes' => 'nullable|string'
        ]);

        $order = $this->orderService->createOrder($validated);
        return response()->json($order, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'OrderDate' => 'required|date',
            'Status' => 'required|string|max:50',
            'TotalAmount' => 'required|numeric',
            'Notes' => 'nullable|string'
        ]);

        $order = $this->orderService->updateOrder($id, $validated);
        return response()->json($order);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->orderService->deleteOrder($id);
        return response()->json(['message' => 'Pedido eliminado correctamente']);
    }
}
