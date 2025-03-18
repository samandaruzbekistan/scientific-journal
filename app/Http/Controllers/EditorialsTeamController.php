<?php

namespace App\Http\Controllers;

use App\Models\EditorialsTeam;
use App\Repositories\ArticleTypeRepository;
use App\Repositories\EditorialRepository;
use App\Repositories\EditorialsTeamRepository;
use Illuminate\Http\Request;

class EditorialsTeamController extends Controller
{
    public function __construct(
        protected EditorialRepository $editorialRepository,
        protected EditorialsTeamRepository $editorialsTeamRepository,
        protected ArticleTypeRepository $articleTypeRepository
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->editorialsTeamRepository->getAll());
    }

    public function add_editorial(Request $request){
        $request->validate([
            'editorial_id' => 'required|integer|exists:editorials,id',
            'team_id' => 'required|integer|exists:editorials_teams,id',
        ]);

        $editorial = $this->editorialRepository->getById($request->editorial_id);
        $team = $this->editorialsTeamRepository->getById($request->team_id);

        if (!$editorial) {
            return response()->json([
                'message' => 'Taxriryat a\'zosi topilmadi',
            ], 404);
        }

        if (!$team) {
            return response()->json([
                'message' => 'Taxriryat a\'zolari jamoasi topilmadi',
            ], 404);
        }

        $array = json_decode($team->json_data);

        if (in_array($editorial->id, $array)) {
            return response()->json([
                'message' => 'Taxriryat a\'zosi allaqachon qo\'shilgan',
            ], 404);
        }

        $array[] = $editorial->id;

        $team->json_data = json_encode($array);

        $team->save();

        return response()->json([
            'message' => 'Taxriryat a\'zosi muvaffaqiyatli qo\'shildi',
        ]);
    }

    public function delete_editorial(Request $request){
        $request->validate([
            'editorial_id' => 'required|integer|exists:editorials,id',
            'team_id' => 'required|integer|exists:editorials_teams,id',
        ]);

        $editorial = $this->editorialRepository->getById($request->editorial_id);
        $team = $this->editorialsTeamRepository->getById($request->team_id);

        if (!$editorial) {
            return response()->json([
                'message' => 'Taxriryat a\'zosi topilmadi',
            ], 404);
        }

        if (!$team) {
            return response()->json([
                'message' => 'Taxriryat a\'zolari jamoasi topilmadi',
            ], 404);
        }

        $array = json_decode($team->json_data);

        if (!in_array($editorial->id, $array)) {
            return response()->json([
                'message' => 'Taxriryat a\'zosi topilmadi',
            ], 404);
        }

        $array = array_diff($array, [$editorial->id]);

        $team->json_data = json_encode($array);

        $team->save();

        return response()->json([
            'message' => 'Taxriryat a\'zosi muvaffaqiyatli o\'chirildi',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'article_type_id' => 'required|integer|exists:article_types,id',
        ]);

        $team = $this->editorialsTeamRepository->getByTypeId($validated['article_type_id']);

        if ($team) {
            return response()->json([
                'message' => 'Taxriryat a\'zolari jamoasi allaqachon mavjud',
            ], 404);
        }

        $article_type = $this->articleTypeRepository->getArticleType($validated['article_type_id']);

        if (!$article_type) {
            return response()->json([
                'message' => 'Maqola turi topilmadi',
            ], 404);
        }

        $validated['name'] = $article_type->name_uz;
        $validated['json_data'] = "[]";

        $editorial = $this->editorialsTeamRepository->create($validated);

        return response()->json([
            'data' => $editorial,
            'message' => 'Taxriryat a\'zolari jamoasi muvaffaqiyatli yaratildi',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $team = $this->editorialsTeamRepository->getById($id);

        if (!$team) {
            return response()->json([
                'message' => 'Taxriryat a\'zolari jamoasi topilmadi',
            ], 404);
        }

        return response()->json($team);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditorialsTeam $editorialsTeam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $team = $this->editorialsTeamRepository->getByTypeId($id);

        if (!$team) {
            return response()->json([
                'message' => 'Taxriryat a\'zolari jamoasi topilmadi',
            ], 404);
        }

        $this->editorialsTeamRepository->update($request->all(), $id);

        return response()->json([
            'message' => 'Taxriryat a\'zolari jamoasi ma\'lumotlari muvaffaqiyatli yangilandi',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->editorialsTeamRepository->delete($id);
        return response()->json([
            'message' => 'Taxriryat a\'zolari jamoasi muvaffaqiyatli o\'chirildi',
        ]);
    }
}
