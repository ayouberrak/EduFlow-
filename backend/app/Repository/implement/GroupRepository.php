<?php

namespace App\Repository\Implement;

use App\Repository\interfaces\GroupRepositoryInterface;
use App\Models\Group;

class GroupRepository implements GroupRepositoryInterface {
    public function all() {
        return Group::all();
    }

    public function create(array $data) {
        return Group::create($data);
    }

    public function find($id) {
        return Group::find($id);
    }

    public function update($id, array $data) {
        $group = Group::find($id);
        $group->update($data);
        return $group;
    }

    public function delete($id) {
        $group = Group::find($id);
        $group->delete();
        return $group;
    }

    public function getAvailableGroup($courseId) {
        return Group::where('course_id', $courseId)
            ->withCount('enrollments')
            ->having('enrollments_count', '<', 25)
            ->first();
    }

    public function getCountByCourse($courseId) {
        return Group::where('course_id', $courseId)->count();
    }
}
