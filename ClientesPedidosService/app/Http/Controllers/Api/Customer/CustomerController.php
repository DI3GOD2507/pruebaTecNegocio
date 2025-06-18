<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\CustomerService;
use App\Services\PersonService;
use Illuminate\Support\Facades\DB;

class CustomerController
{
    protected CustomerService $customerService;
    protected PersonService $personService;

    public function __construct(CustomerService $customerService, PersonService $personService)
    {
        $this->customerService = $customerService;
        $this->personService = $personService;
    }

/**
 * @OA\Get(
 *     path="/api/customers",
 *     summary="Obtener lista de clientes",
 *     tags={"Clientes"},
 *     @OA\Response(response=200, description="Lista de clientes")
 * )
 */
    public function index(): JsonResponse

    {
        $customers = $this->customerService->getAllCustomers();
        return response()->json($customers);
    }

/**
 * @OA\Get(
 *     path="/api/customers/{id}",
 *     summary="Obtener un cliente por ID",
 *     tags={"Clientes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Cliente encontrado"),
 *     @OA\Response(response=404, description="Cliente no encontrado")
 * )
 */
    public function show(int $id): JsonResponse

    {
        $customer = $this->customerService->getCustomerById($id);
        if (!$customer) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
        return response()->json($customer);
    }

/**
 * @OA\Post(
 *     path="/api/customers",
 *     summary="Crear un nuevo cliente",
 *     tags={"Clientes"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"FirstName", "LastName", "DocumentNumber"},
 *             @OA\Property(property="FirstName", type="string", example="Juan"),
 *             @OA\Property(property="LastName", type="string", example="Pérez"),
 *             @OA\Property(property="DocumentNumber", type="string", example="0102030405"),
 *             @OA\Property(property="Email", type="string", example="juan@example.com"),
 *             @OA\Property(property="Phone", type="string", example="0991234567")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Cliente creado correctamente"),
 *     @OA\Response(response=422, description="Validación fallida")
 * )
 */
    public function store(Request $request): JsonResponse

    {
        $validated = $request->validate([
            'FirstName' => 'required|string|max:100',
            'LastName' => 'required|string|max:100',
            'DocumentNumber' => 'required|string|max:50',
            'Email' => 'nullable|email',
            'Phone' => 'nullable|string|max:50',
        ]);

        return DB::transaction(function () use ($validated) {
            $person = $this->personService->createPerson($validated);
            $customer = $this->customerService->createCustomer([
                'PersonId' => $person->Id,
                'Status' => 1
            ]);
            return response()->json($customer, 201);
        });
    }

/**
 * @OA\Put(
 *     path="/api/customers/{id}",
 *     summary="Actualizar información de un cliente",
 *     tags={"Clientes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"FirstName", "LastName", "DocumentNumber"},
 *             @OA\Property(property="FirstName", type="string", example="Juan"),
 *             @OA\Property(property="LastName", type="string", example="Pérez"),
 *             @OA\Property(property="DocumentNumber", type="string", example="0102030405"),
 *             @OA\Property(property="Email", type="string", example="juan@example.com"),
 *             @OA\Property(property="Phone", type="string", example="0991234567")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Cliente actualizado correctamente"),
 *     @OA\Response(response=404, description="Cliente no encontrado")
 * )
 */
    public function update(Request $request, int $id): JsonResponse

    {
        $validated = $request->validate([
            'FirstName' => 'required|string|max:100',
            'LastName' => 'required|string|max:100',
            'DocumentNumber' => 'required|string|max:50',
            'Email' => 'nullable|email',
            'Phone' => 'nullable|string|max:50',
        ]);

        return DB::transaction(function () use ($validated, $id) {
            $customer = $this->customerService->getCustomerById($id);
            if (!$customer) {
                return response()->json(['message' => 'Cliente no encontrado'], 404);
            }

            $this->personService->updatePerson($customer->person->Id, $validated);
            $this->customerService->updateCustomer($id, []);

            return response()->json(['message' => 'Cliente actualizado correctamente']);
        });
    }

/**
 * @OA\Delete(
 *     path="/api/customers/{id}",
 *     summary="Eliminar un cliente",
 *     tags={"Clientes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Cliente eliminado correctamente"),
 *     @OA\Response(response=404, description="Cliente no encontrado")
 * )
 */
    public function destroy(int $id): JsonResponse

    {
        $customer = $this->customerService->getCustomerById($id);
        if (!$customer) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return DB::transaction(function () use ($customer, $id) {
            $this->customerService->deleteCustomer($id);
            $this->personService->deletePerson($customer->person->Id);
            return response()->json(['message' => 'Cliente eliminado correctamente']);
        });
    }
}
