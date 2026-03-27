<?php

namespace App\Repository\Implement;

use App\Repository\interfaces\EnrollmentRepositoryInterface;
use App\Models\Enrollment;

class EnrollmentRepository implements EnrollmentRepositoryInterface {
    public function all() {
        return Enrollment::all();
    }

    public function create(array $data) {
        return Enrollment::create($data);
    }

    public function find($id) {
        return Enrollment::find($id);
    }

    public function update($id, array $data) {
        $enrollment = Enrollment::find($id);
        $enrollment->update($data);
        return $enrollment;
    }

    public function delete($id) {
        $enrollment = Enrollment::find($id);
        $enrollment->delete();
        return $enrollment;
    }

    public function findByCourse($courseId) {
        return Enrollment::where('course_id', $courseId)->get();
    }

    public function getEnrollmentCountByTeacher($teacherId) {
        return Enrollment::whereHas('course', function($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })->count();
    }
}
