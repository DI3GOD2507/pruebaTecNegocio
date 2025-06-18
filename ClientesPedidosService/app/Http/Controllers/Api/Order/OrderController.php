<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\OrderService;

class OrderController
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

/**
 * @OA\Get(
 *     path="/api/orders",
 *     summary="Obtener lista de pedidos",
 *     tags={"Pedidos"},
 *     @OA\Response(response=200, description="Lista de pedidos")
 * )
 */
    public function index(): JsonResponse

    {
        return response()->json($this->orderService->getAllOrders());
    }

/**
 * @OA\Get(
 *     path="/api/orders/{id}",
 *     summary="Obtener un pedido por ID",
 *     tags={"Pedidos"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Pedido encontrado"),
 *     @OA\Response(response=404, description="Pedido no encontrado")
 * )
 */
    public function show(int $id): JsonResponse

    {
        $order = $this->orderService->getOrderById($id);
        if (!$order) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }
        return response()->json($order);
    }

/**
 * @OA\Post(
 *     path="/api/orders",
 *     summary="Crear un nuevo pedido",
 *     tags={"Pedidos"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"CustomerId", "OrderDate", "Status", "TotalAmount"},
 *             @OA\Property(property="CustomerId", type="integer", example=1),
 *             @OA\Property(property="OrderDate", type="string", format="date-time", example="2024-06-18T12:00:00Z"),
 *             @OA\Property(property="Status", type="string", example="pending"),
 *             @OA\Property(property="TotalAmount", type="number", format="float", example=99.99),
 *             @OA\Property(property="Notes", type="string", example="Pedido urgente")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Pedido creado correctamente"),
 *     @OA\Response(response=422, description="Validación fallida")
 * )
 */
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

/**
 * @OA\Put(
 *     path="/api/orders/{id}",
 *     summary="Actualizar información de un pedido",
 *     tags={"Pedidos"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"OrderDate", "Status", "TotalAmount"},
 *             @OA\Property(property="OrderDate", type="string", format="date-time", example="2024-06-18T12:00:00Z"),
 *             @OA\Property(property="Status", type="string", example="completed"),
 *             @OA\Property(property="TotalAmount", type="number", format="float", example=49.99),
 *             @OA\Property(property="Notes", type="string", example="Cliente cambió producto")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Pedido actualizado correctamente"),
 *     @OA\Response(response=404, description="Pedido no encontrado")
 * )
 */
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

/**
 * @OA\Delete(
 *     path="/api/orders/{id}",
 *     summary="Eliminar un pedido",
 *     tags={"Pedidos"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Pedido eliminado correctamente"),
 *     @OA\Response(response=404, description="Pedido no encontrado")
 * )
 */
    public function destroy(int $id): JsonResponse

    {
        $this->orderService->deleteOrder($id);
        return response()->json(['message' => 'Pedido eliminado correctamente']);
    }
}
