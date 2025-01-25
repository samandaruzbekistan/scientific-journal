<?php

namespace App\Http\Controllers;

use App\Models\AcademicDegree;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\EMailService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct(protected UserRepository $userRepository, protected EMailService $mailService)
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

        $validatedData['remember_token'] = md5($validatedData['email'] . time());

        $user = $this->userRepository->create($validatedData);

        if($user){
            $this->mailService->sendUserEmailVerification($user->full_name, $user->id, $user->email, $user->remember_token);
        }

        return response()->json([
            'message_en' => 'User registered successfully. Please verify your email.',
            'message_uz' => 'Foydalanuvchi muvaffaqiyatli ro\'yxatdan o\'tdi. Iltimos, elektron pochtangizni tasdiqlang.',
            'message_ru' => 'Пользователь успешно зарегистрирован. Пожалуйста, подтвердите свою электронную почту.',
        ], 201);
    }

    public function verify_email(Request $request){
        $request->validate([
            'token' => 'required|string',
            'id' => 'required|integer|exists:users,id',
        ]);
        $user = $this->userRepository->getById($request->id);
        if($user->remember_token == $request->token){
            $user->email_verified_at = now();
            $user->save();
            $token = $user->createToken($user['email'])->plainTextToken;
            return response()->json([
                'message_en' => 'Email verified successfully.',
                'message_uz' => 'Elektron pochta muvaffaqiyatli tasdiqlandi.',
                'message_ru' => 'Электронная почта успешно подтверждена.',
                'token' => $token,
                'user' => $user,
            ]);
        }
        return response()->json([
            'message_en' => 'Invalid token.',
            'message_uz' => 'Noto\'g\'ri token.',
            'message_ru' => 'Неверный токен.',
        ], 400);
    }

    public function resend_email_verification(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = $this->userRepository->getByEmail($request->email);
        if($user){
            $this->mailService->sendUserEmailVerification($user->full_name, $user->id, $user->email, $user->remember_token);
            return response()->json([
                'message_en' => 'Verification email sent successfully.',
                'message_uz' => 'Tasdiqlash elektron pochtasi muvaffaqiyatli yuborildi.',
                'message_ru' => 'Письмо с подтверждением успешно отправлено.',
            ]);
        }
        return response()->json([
            'message_en' => 'User not found.',
            'message_uz' => 'Foydalanuvchi topilmadi.',
            'message_ru' => 'Пользователь не найден.',
        ], 404);
    }

}
