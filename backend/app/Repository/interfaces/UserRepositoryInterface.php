<?php

namespace App\Repository\interfaces;

interface UserRepositoryInterface {
    public function all();
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function findByEmail(string $email);
    public function attachDomains($userId, array $domainIds);
}