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

    public function get_active_journal()
    {
        return response()->json($this->journalRepository->getActiveJournal(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $active_journal = $this->journalRepository->getActiveJournal();

        if($active_journal){
            return response()->json([
                'message_uz' => 'Aktiv jurnal mavjud',
                'message_ru' => 'Активный журнал существует',
                'message_en' => 'Active journal exists'
            ], 400);
        }

        $validated = $request->validate([
            'name_uz' => 'required|string',
            'name_ru' => 'required|string',
            'name_en' => 'required|string',
            'year' => 'required|string',
            'number' => 'required|string',
            'issn' => 'required|string',
            'cover_image_uz' => 'required|file|mimes:jpeg,png,jpg',
            'cover_image_ru' => 'required|file|mimes:jpeg,png,jpg',
            'cover_image_en' => 'required|file|mimes:jpeg,png,jpg',
            'template' => 'required|file|mimes:doc,docx,pdf',
        ]);

        $file = $request->file('cover_image_uz')->getClientOriginalExtension();
        $name = md5(microtime());
        $photo_name = $name.".".$file;
        $request->file('cover_image_uz')->move('images/journal_covers/',$photo_name);
        $validated['cover_image_uz'] = 'images/journal_covers/'.$photo_name;

        $file = $request->file('cover_image_ru')->getClientOriginalExtension();
        $name = md5(microtime());
        $photo_name = $name.".".$file;
        $request->file('cover_image_ru')->move('images/journal_covers/',$photo_name);
        $validated['cover_image_ru'] = 'images/journal_covers/'.$photo_name;

        $file = $request->file('cover_image_en')->getClientOriginalExtension();
        $name = md5(microtime());
        $photo_name = $name.".".$file;
        $request->file('cover_image_en')->move('images/journal_covers/',$photo_name);
        $validated['cover_image_en'] = 'images/journal_covers/'.$photo_name;

        $template = $request->file('template')->getClientOriginalExtension();
        $name = md5(microtime());
        $template_name = $name.".".$template;
        $request->file('template')->move('images/journal_templates/',$template_name);
        $validated['template'] = 'images/journal_templates/'.$template_name;

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

    public function change_status(string $id)
    {
        $journal = $this->journalRepository->getJournal($id);
        if(!$journal){
            return response()->json([
                'message_uz' => 'Jurnal topilmadi',
                'message_ru' => 'Журнал не найден',
                'message_en' => 'Journal not found'
            ], 404);
        }
        $this->journalRepository->change_status_to_completed($id);
        return response()->json([
            'message_uz' => 'Jurnal muvaffaqiyatli yakunlandi',
            'message_ru' => 'Журнал успешно завершен',
            'message_en' => 'Journal completed successfully'
        ], 200);
    }

    public function to_active(string $id)
    {
        $journal = $this->journalRepository->getJournal($id);
        if(!$journal){
            return response()->json([
                'message_uz' => 'Jurnal topilmadi',
                'message_ru' => 'Журнал не найден',
                'message_en' => 'Journal not found'
            ], 404);
        }
        if($journal->status == 'active'){
            $this->journalRepository->change_status_to_completed($id);
            return response()->json([
                'message_uz' => 'Jurnal holati o\'zgartirildi',
                'message_ru' => 'Статус журнала изменен',
                'message_en' => 'Journal status changed'
            ], 400);
        }
        $this->journalRepository->change_status_to_completed_all();
        $this->journalRepository->change_status_to_active($id);
        return response()->json([
            'message_uz' => 'Jurnal muvaffaqiyatli faol holatga o\'tadi',
            'message_ru' => 'Журнал успешно активирован',
            'message_en' => 'Journal activated successfully'
        ], 200);
    }
}
