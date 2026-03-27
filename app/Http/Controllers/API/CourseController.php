<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\course\CreateRequest;
use App\Repository\interfaces\CourseRepositoryInterface;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private $repo;

    public function __construct(CourseRepositoryInterface $repo) {
        $this->repo = $repo;
    }


    public function index(){
        $courses = $this->repo->all();
        return response()->json([
            'message' => 'Courses retrieved successfully',
            'courses' => $courses
        ], 200);
    }

    public function show($id){
        
        $course = $this->repo->find($id);

        if(!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }
        return response()->json([
            'message' => 'Course retrieved successfully',
            'course' => $course
        ], 200);
    }

    public function store(CreateRequest $request){
        $request->validated();

        $course = $this->repo->create($request->validated());
        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course
        ], 201);
    }

    public function update(CreateRequest $request, $id){
        $request->validated();

        $course = $this->repo->update($id, $request->validated());


        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course
        ], 200);
    }

    public function destroy($id){
        $course = $this->repo->delete($id);

        return response()->json([
            'message' => 'Course deleted successfully',
            'course' => $course
        ], 200);
    }
}
