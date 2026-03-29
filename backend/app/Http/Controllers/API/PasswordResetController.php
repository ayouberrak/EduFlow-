<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\ForgotRequest;
use App\Http\Requests\auth\ResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function forgot(ForgotRequest $request)
    {
        $request->validated();

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::RESET_LINK_SENT) {
            response()->json(['message' => 'Email envoyé']);
        }else{
            response()->json(['error' => 'Erreur'], 400);
        }
    }

    public function reset(ResetRequest $request)
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if($status === Password::PASSWORD_RESET) {
            response()->json(['message' => 'Mot de passe modifié']);
        }else{
            response()->json(['error' => 'Erreur'], 400);
        }
    }
}
