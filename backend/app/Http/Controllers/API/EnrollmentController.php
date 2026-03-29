<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Repository\interfaces\CourseRepositoryInterface;
use App\Repository\interfaces\EnrollmentRepositoryInterface;
use App\Repository\interfaces\GroupRepositoryInterface;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;


class EnrollmentController extends Controller
{
    private $enrollmentRepo;
    private $courseRepo;
    private $groupRepo;

    public function __construct(
        EnrollmentRepositoryInterface $enrollmentRepo,
        CourseRepositoryInterface $courseRepo,
        GroupRepositoryInterface $groupRepo
    ) {
        $this->enrollmentRepo = $enrollmentRepo;
        $this->courseRepo = $courseRepo;
        $this->groupRepo = $groupRepo;
    }

    public function enroll(Request $request, $courseId)
    {
        $user = auth()->user();
        $course = $this->courseRepo->find($courseId);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        if ($user->role !== 'student') {
            return response()->json(['error'=>'Only students can enroll'],403);
        }

        // Check if already enrolled (assuming user relationship or repository check)
        // Here we can use enrollmentRepo to check
        // For simplicity, let's keep the user object check if possible or add to repo
        
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $course->price * 100, // cents
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id
            ]
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret
        ]);
    
    }


    public function confirmEnrollment(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            return response()->json(['error'=>'Only students can enroll'], 403);
        }

        $request->validate([
            'payment_intent_id' => 'required|string'
        ]);

        $paymentIntentId = $request->input('payment_intent_id');

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()], 400);
        }

        if ($paymentIntent->status !== 'succeeded') {
            return response()->json(['error'=>'Payment not successful'], 400);
        }

        $courseId = $paymentIntent->metadata->course_id;

        // Create enrollment
        $enrollment = $this->enrollmentRepo->create([
            'user_id' => $user->id,
            'course_id' => $courseId
        ]);

        // Assign group automatically
        $this->assignGroup($enrollment);

        return response()->json(['message'=>'Enrollment successful']);
    }

    private function assignGroup($enrollment)
    {
        $group = $this->groupRepo->getAvailableGroup($enrollment->course_id);

        if (!$group) {
            $group = $this->groupRepo->create([
                'course_id' => $enrollment->course_id,
                'name' => 'Group ' . ($this->groupRepo->getCountByCourse($enrollment->course_id) + 1)
            ]);
        }

        $this->enrollmentRepo->update($enrollment->id, [
            'group_id' => $group->id
        ]);
    }

    public function unenroll($courseId)
    {
        $user = auth()->user();

        $enrollment = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if ($enrollment) {
            $enrollment->delete();
            return response()->json(['message' => 'Unenrolled successfully']);
        }

        return response()->json(['message' => 'Not enrolled'], 404);
    }


    public function getStudentByCourse($courseId)
    {
        $enrollments = $this->enrollmentRepo->findByCourse($courseId);

        $students = [];
        foreach ($enrollments as $enrollment) {
            $students[] = $enrollment->user;
        }

        return response()->json([
            'message' => 'Students retrieved successfully',
            'students' => $students
        ], 200);
    }

}
