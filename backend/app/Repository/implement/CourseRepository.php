<?php

namespace App\Repository\Implement;

use App\Repository\interfaces\CourseRepositoryInterface;
use App\Models\Course;

class CourseRepository implements CourseRepositoryInterface {
    public function all() {
        return Course::all();
    }

    public function create(array $data) {
        return Course::create($data);
    }

    public function find($id) {
        return Course::find($id);
    }

    public function update($id, array $data) {
        $course = Course::find($id);
        $course->update($data);
        return $course;
    }

    public function delete($id) {
        $course = Course::find($id);
        $course->delete();
        return $course;
    }

    public function findByDomains(array $domainIds) {
        return Course::whereIn('domain_id', $domainIds)->get();
    }

    public function getByTeacher($teacherId) {
        return Course::where('teacher_id', $teacherId)->get();
    }
}