<?php

namespace App\Services;

use App\Repositories\Interfaces\PersonRepositoryInterface;

class PersonService
{
    protected PersonRepositoryInterface $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function getPersonById(int $id)
    {
        return $this->personRepository->find($id);
    }

    public function createPerson(array $data)
    {
        return $this->personRepository->create($data);
    }

    public function updatePerson(int $id, array $data)
    {
        return $this->personRepository->update($id, $data);
    }

    public function deletePerson(int $id): bool
    {
        return $this->personRepository->delete($id);
    }
}
