<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Support\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function all(): Collection
    {
        return Customer::with('person')->get();
    }

    public function find(int $id)
    {
        return Customer::with('person')->find($id);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function delete(int $id): bool
    {
        return Customer::destroy($id) > 0;
    }
}
