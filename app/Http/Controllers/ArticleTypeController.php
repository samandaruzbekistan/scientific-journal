<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleTypeRepository;
use Illuminate\Http\Request;

class ArticleTypeController extends Controller
{
    //
    public function __construct(protected ArticleTypeRepository $articleTypeRepository)
    {
    }

    public function index()
    {
        return response()->json($this->articleTypeRepository->getArticleTypes(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_uz' => 'required|string',
            'name_ru' => 'required|string',
            'name_en' => 'required|string',
        ]);

        $this->articleTypeRepository->createArticleType($validated);

        return response()->json([
            'message_en' => 'Article type created successfully',
            'message_ru' => 'Тип статьи успешно создан',
            'message_uz' => 'Maqola turi muvaffaqiyatli yaratildi',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name_uz' => 'required|string',
            'name_ru' => 'required|string',
            'name_en' => 'required|string',
        ]);

        $this->articleTypeRepository->updateArticleType($id, $validated);

        return response()->json([
            'message_en' => 'Article type updated successfully',
            'message_ru' => 'Тип статьи успешно обновлен',
            'message_uz' => 'Maqola turi muvaffaqiyatli yangilandi',
        ], 200);
    }

    public function destroy($id)
    {
        $this->articleTypeRepository->deleteArticleType($id);

        return response()->json([
            'message_en' => 'Article type deleted successfully',
            'message_ru' => 'Тип статьи успешно удален',
            'message_uz' => 'Maqola turi muvaffaqiyatli o\'chirildi',
        ], 200);
    }

    public function show($id)
    {
        return response()->json($this->articleTypeRepository->getArticleType($id), 200);
    }
}
