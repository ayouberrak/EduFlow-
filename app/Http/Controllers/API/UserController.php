<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\Domains;
use App\Repository\interfaces\UserRepositoryInterface;
use App\Repository\interfaces\CourseRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepo;
    private $courseRepo;

    public function __construct(UserRepositoryInterface $userRepo, CourseRepositoryInterface $courseRepo) {
        $this->userRepo = $userRepo;
        $this->courseRepo = $courseRepo;
    }

    public function addDomains(Domains $request)
    {
        $request->validated();

        $user = auth()->user();

        if ($user->role !== 'student') {
            return response()->json(['error' => 'Only students can add domains'], 403);
        }
        
        $this->userRepo->attachDomains($user->id, $request->domains);

        return response()->json([
            'message' => 'Domains added successfully'
        ]);
    }

    public function recommendCourses()
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            return response()->json(['error' => 'Only students can get course recommendations'], 403);
        }

        $domains = $user->domains()->pluck('id')->toArray();
        $courses = $this->courseRepo->findByDomains($domains);

        return response()->json([
            'message' => 'Recommended courses retrieved successfully',
            'courses' => $courses
        ]);
    }
}
