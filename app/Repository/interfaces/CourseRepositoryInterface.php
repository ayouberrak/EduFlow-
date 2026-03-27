<?php

namespace App\Repository\interfaces;

interface CourseRepositoryInterface {
    public function all();
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function findByDomains(array $domainIds);
    public function getByTeacher($teacherId);
}