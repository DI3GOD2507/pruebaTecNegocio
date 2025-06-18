<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(): Collection
    {
        return Order::with('order')->get();
    }

    public function find(int $id)
    {
        return Order::with('order')->find($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function update(int $id, array $data)
    {
        $order = Order::findOrFail($id);
        $order->update($data);
        return $order;
    }

    public function delete(int $id): bool
    {
        return Order::destroy($id) > 0;
    }
}
