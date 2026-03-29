<?php

namespace App\Repository\Implement;

use App\Repository\interfaces\DomainRepositoryInterface;
use App\Models\Domain;

class DomainRepository implements DomainRepositoryInterface {
    public function all() {
        return Domain::all();
    }

    public function create(array $data) {
        return Domain::create($data);
    }

    public function find($id) {
        return Domain::find($id);
    }

    public function update($id, array $data) {
        $domain = Domain::find($id);
        $domain->update($data);
        return $domain;
    }

    public function delete($id) {
        $domain = Domain::find($id);
        $domain->delete();
        return $domain;
    }
}
