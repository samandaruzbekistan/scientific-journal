<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(protected UserRepository $userRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated_data = $request->validate([
            'academic_degree_id' => 'string|exists:academic_degrees,id|nullable',
            'country' => 'string|nullable',
            'region' => 'string|nullable',
            'city' => 'string|nullable',
            'phone' => 'string|nullable',
            'passport_number' => 'string|nullable',
            'institution' => 'string|nullable',
        ]);

        $user = $this->userRepository->getById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user = $this->userRepository->update($id, $validated_data);

        return response()->json(['user' => $user, 'en' => 'User updated successfully', 'uz' => 'Foydalanuvchi muvaffaqiyatli yangilandi', 'ru' => 'Пользователь успешно обновлен']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
