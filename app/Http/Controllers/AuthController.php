<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:seo,teacher,user',
        ]);

        $user = $this->userRepository->create($validatedData);

        $token = $user->createToken($user['password'])->plainTextToken;

        return response()->json(['message' => 'User registered successfully','token' => $token], 201);
    }


    public function verifyEmail(Request $request)
    {
        $user = User::find($request->id);

        if (!$user || $user->email_verified_at) {
            return response()->json(['message' => 'Invalid or already verified.'], 400);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email resent.'], 200);
    }

}
