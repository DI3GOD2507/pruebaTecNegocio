<?php

namespace App\Repositories;

use App\Models\Person;
use App\Repositories\Interfaces\PersonRepositoryInterface;
use Illuminate\Support\Collection;

class PersonRepository implements PersonRepositoryInterface
{
    public function all(): Collection
    {
        return Person::with('person')->get();
    }

    public function find(int $id)
    {
        return Person::with('person')->find($id);
    }

    public function create(array $data)
    {
        return Person::create($data);
    }

    public function update(int $id, array $data)
    {
        $person = Person::findOrFail($id);
        $person->update($data);
        return $person;
    }

    public function delete(int $id): bool
    {
        return Person::destroy($id) > 0;
    }
}
