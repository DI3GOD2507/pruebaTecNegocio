<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\CustomerService;
use App\Services\PersonService;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    protected CustomerService $customerService;
    protected PersonService $personService;

    public function __construct(CustomerService $customerService, PersonService $personService)
    {
        $this->customerService = $customerService;
        $this->personService = $personService;
    }

    public function index(): JsonResponse
    {
        $customers = $this->customerService->getAllCustomers();
        return response()->json($customers);
    }

    public function show(int $id): JsonResponse
    {
        $customer = $this->customerService->getCustomerById($id);
        if (!$customer) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
        return response()->json($customer);
    }

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
