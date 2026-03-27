<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function add($course_id)
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            return response()->json(['error' => 'Only students'], 403);
        }

        $user->favorites()->syncWithoutDetaching($course_id);
    }

    public function remove($course_id)
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            return response()->json(['error' => 'Only students'], 403);
        }

        $user->favorites()->detach($course_id);

        return response()->json([
            'message' => 'Course removed from favorites successfully'
        ], 200);
    }


    public function index()
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            return response()->json(['error' => 'Only students'], 403);
        }

        $favorites = $user->favorites()->get();

        return response()->json([
            'message' => 'Favorite courses retrieved successfully',
            'favorites' => $favorites
        ], 200);
    }
}
