<?php

namespace App\Http\Controllers;

use App\Repositories\EditorialRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class EditorialController extends Controller
{
    public function __construct(protected EditorialRepository $editorialRepository)
    {
    }

    public function index()
    {
        return response()->json($this->editorialRepository->getAll());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255',
            'article_type_id' => 'required|integer|exists:article_types,id',
        ]);

        $editorial_old = $this->editorialRepository->getByEmail($validated['email']);

        if ($editorial_old) {
            return response()->json([
                'message' => 'Taxriryat a\'zosi allaqachon mavjud',
            ], 404);
        }

        $password = Hash::make($validated['password']);

        $validated['password'] = $password;

        $editorial = $this->editorialRepository->create($validated);

        return response()->json([
            'data' => $editorial,
            'message' => 'Taxriryat a\'zosi muvaffaqiyatli yaratildi',
        ]);
    }

    public function show($id)
    {
        $editorial = $this->editorialRepository->getById($id);

        if (!$editorial) {
            return response()->json([
                'message' => 'Taxriryat a\'zosi topilmadi',
            ], 404);
        }

        return response()->json($this->editorialRepository->getById($id));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $editorial = $this->editorialRepository->getById($id);

        if (!$editorial) {
            return response()->json([
                'message' => 'Taxriryat a\'zosi topilmadi',
            ], 404);
        }

        $password = Hash::make($data['password']);
        $data['password'] = $password;
        $this->editorialRepository->update($data, $id);

        return response()->json([
            'message' => 'Taxriryat a\'zosi ma\'lumotlari muvaffaqiyatli yangilandi',
        ]);
    }

    public function destroy($id)
    {
        $editorial = $this->editorialRepository->getById($id);

        if (!$editorial) {
            return response()->json([
                'message' => 'Taxriryat a\'zosi topilmadi',
            ], 404);
        }

        $this->editorialRepository->delete($id);

        return response()->json([
            'message' => 'Taxriryat a\'zosi muvaffaqiyatli o\'chirildi',
        ]);
    }
}
