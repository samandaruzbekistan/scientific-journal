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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'orcid' => 'required|string',
            'phone' => 'required|string',
            'locale' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = $this->userRepository->getByEmail($validatedData['email']);

        if ($user) {
            return response()->json([
                'en' => 'User with this email already exists.',
                'uz' => 'Bu elektron pochta bilan foydalanuvchi allaqachon mavjud.',
                'ru' => 'Пользователь с таким адресом электронной почты уже существует.',
                'user_status' => $user->status,
            ], 422);
        }
        elseif ($this->userRepository->getByPhone($validatedData['phone'])) {
            return response()->json([
                'en' => 'User with this phone number already exists.',
                'uz' => 'Bu telefon raqami bilan foydalanuvchi allaqachon mavjud.',
                'ru' => 'Пользователь с таким номером телефона уже существует.',
            ], 422);
        }

        $validatedData['role'] = 'user';
        $validatedData['password'] = Hash::make($validatedData['password']);

        $validatedData['remember_token'] = md5($validatedData['email'] . time());

        $user = $this->userRepository->create($validatedData);

        $full_name = $user->first_name . ' ' . $user->last_name;

        if($user){
            $this->mailService->sendUserEmailVerification($full_name, $user->id, $user->email, $user->remember_token, $validatedData['locale']);
        }

        return response()->json([
            'en' => 'User registered successfully. Please verify your email.',
            'uz' => 'Foydalanuvchi muvaffaqiyatli ro\'yxatdan o\'tdi. Iltimos, elektron pochtangizni tasdiqlang.',
            'ru' => 'Пользователь успешно зарегистрирован. Пожалуйста, подтвердите свою электронную почту.',
        ], 201);
    }

    public function verify_email(Request $request){
        $request->validate([
            'token' => 'required|string',
            'id' => 'required|string|exists:users,id',
        ]);
        $user = $this->userRepository->getById($request->id);
        if($user->remember_token == $request->token){
            $user->email_verified_at = now();
            $user->status = "pending";
            $user->save();
            $token = $user->createToken($user['email'])->plainTextToken;
            return response()->json([
                'en' => 'Email verified successfully.',
                'uz' => 'Elektron pochta muvaffaqiyatli tasdiqlandi.',
                'ru' => 'Электронная почта успешно подтверждена.',
                'token' => $token,
                'user' => $user,
            ], 200);
        }
        return response()->json([
            'en' => 'Invalid token.',
            'uz' => 'Noto\'g\'ri token.',
            'ru' => 'Неверный токен.',
        ], 422);
    }

    public function resend_email_verification(Request $request){
        $request->validate([
            'email' => 'required|email',
            'locale' => 'required|string',
        ]);
        $user = $this->userRepository->getByEmail($request->email);
        $full_name = $user->first_name . ' ' . $user->last_name;
        if($user){
            $this->mailService->sendUserEmailVerification($full_name, $user->id, $user->email, $user->remember_token, $request['locale']);
            return response()->json([
                'en' => 'Verification email sent successfully.',
                'uz' => 'Tasdiqlash elektron pochtasi muvaffaqiyatli yuborildi.',
                'ru' => 'Письмо с подтверждением успешно отправлено.',
            ]);
        }
        return response()->json([
            'en' => 'User not found.',
            'uz' => 'Foydalanuvchi topilmadi.',
            'ru' => 'Пользователь не найден.',
        ], 404);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = $this->userRepository->getByEmail($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'en' => 'Invalid credentials.',
                'uz' => 'Noto\'g\'ri ma\'lumotlar.',
                'ru' => 'Неверные учетные данные.',
            ], 422);
        }

        $token = $user->createToken($user['email'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

}
