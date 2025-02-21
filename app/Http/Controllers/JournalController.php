<?php

namespace App\Http\Controllers;

use App\Repositories\JournalRepository;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function __construct(protected JournalRepository $journalRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->journalRepository->getJournals(), 200);
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
            'name_uz' => 'required|string',
            'name_ru' => 'required|string',
            'name_en' => 'required|string',
            'year' => 'required|numeric',
            'issn' => 'required|string',
            'cover_image_uz' => 'required|file|mimes:jpeg,png,jpg',
            'cover_image_ru' => 'required|file|mimes:jpeg,png,jpg',
            'cover_image_en' => 'required|file|mimes:jpeg,png,jpg',
        ]);

        $file = $request->file('cover_image_uz')->getClientOriginalExtension();
        $name = md5(microtime());
        $photo_name = $name.".".$file;
        $request->file('cover_image_uz')->move('images/',$photo_name);
        $validated['cover_image_uz'] = 'images/journal_covers'.$photo_name;

        $file = $request->file('cover_image_ru')->getClientOriginalExtension();
        $name = md5(microtime());
        $photo_name = $name.".".$file;
        $request->file('cover_image_ru')->move('images/',$photo_name);
        $validated['cover_image_ru'] = 'images/journal_covers'.$photo_name;

        $file = $request->file('cover_image_en')->getClientOriginalExtension();
        $name = md5(microtime());
        $photo_name = $name.".".$file;
        $request->file('cover_image_en')->move('images/',$photo_name);
        $validated['cover_image_en'] = 'images/journal_covers'.$photo_name;

        $journal = $this->journalRepository->createJournal($validated);

        return response()->json($journal, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $journal = $this->journalRepository->getJournal($id);
        if(!$journal){
            return response()->json([
                'message' => 'Jurnal topilmadi'
            ], 404);
        }
        return response()->json($journal, 200);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
