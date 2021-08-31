<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    //
    public function verify($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
    }

    public function resend(Request $request)

    {

    if ($request->user()->hasVerifiedEmail()) {

    return response()->json(["msg" =>'User already have verified email!'], 422);

    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json(["msg" =>'The notification has been resubmitted']);

    }
}
