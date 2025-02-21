<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct(
        protected AdminRepository $adminRepository,
    )
    {
    }

    public function login(Request $request){
        $admin = $this->adminRepository->getAdmin($request->email);
        if (!$admin){
            return response()->json([
                'message' => "Login yoki parol xato"
            ], 404);
        }
        if (Hash::check($request->input('password'), $admin->password)) {
            $admin->tokens()->delete();
            $token = $admin->createToken($request->email)->plainTextToken;
            return response()->json([
                'admin' => $admin,
                'token' => $token
            ], 200);
        }
        else{
            return response()->json([
                'message' => "Login yoki parol xato"
            ], 404);
        }
    }
}
