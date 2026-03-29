<?php

namespace App\Repository\interfaces;

interface PaymentRepositoryInterface {
    public function all();
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function getTotalRevenueByTeacher($teacherId);
}
