<?php

namespace App\Http\Controllers;

use App\Models\AcademicDegree;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function get_academic_degrees()
    {
        $data = AcademicDegree::all();

        return response()->json($data);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'country' => 'required|string',
            'region' => 'required|string',
            'city' => 'required|string',
            'passport_number' => 'required|string',
            'academic_degree_id' => 'required|integer|exists:academic_degrees,id',
        ]);

        $validatedData['role'] = 'user';

        $user = $this->userRepository->create($validatedData);

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message_en' => 'User registered successfully. Please verify your email.',
            'message_uz' => 'Foydalanuvchi muvaffaqiyatli ro\'yxatdan o\'tdi. Iltimos, elektron pochtangizni tasdiqlang.',
            'message_ru' => 'Пользователь успешно зарегистрирован. Пожалуйста, подтвердите свою электронную почту.',
        ], 201);

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
