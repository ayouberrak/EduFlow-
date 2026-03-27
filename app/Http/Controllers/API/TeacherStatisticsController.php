<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repository\interfaces\CourseRepositoryInterface;
use App\Repository\interfaces\EnrollmentRepositoryInterface;
use App\Repository\interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherStatisticsController extends Controller
{
    private $courseRepository;
    private $enrollmentRepository;
    private $paymentRepository;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        EnrollmentRepositoryInterface $enrollmentRepository,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->courseRepository = $courseRepository;
        $this->enrollmentRepository = $enrollmentRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function index()
    {
        $teacherId = Auth::id();

        $courses = $this->courseRepository->getByTeacher($teacherId);
        $totalCourses = $courses->count();
        $totalEnrollments = $this->enrollmentRepository->getEnrollmentCountByTeacher($teacherId);
        $totalRevenue = $this->paymentRepository->getTotalRevenueByTeacher($teacherId);

        $courseStats = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'enrollments_count' => $course->enrollments()->count(),
                'revenue' => $course->payments()->sum('amount'),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'overview' => [
                    'total_courses' => $totalCourses,
                    'total_students' => $totalEnrollments,
                    'total_revenue' => $totalRevenue,
                ],
                'courses' => $courseStats,
            ]
        ]);
    }
}
