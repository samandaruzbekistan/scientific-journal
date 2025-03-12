<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\JournalRepository;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleRepository $articleRepository,
        protected AuthorRepository $authorRepository,
        protected JournalRepository $journalRepository
    )
    {
    }

    public function store(Request $request){
        $validate_data = $request->validate([
            'title_uz' => 'required|string',
            'title_ru' => 'required|string',
            'title_en' => 'required|string',
            'keywords_uz' => 'required|string',
            'keywords_ru' => 'required|string',
            'keywords_en' => 'required|string',
            'abstract_uz' => 'required|string',
            'abstract_ru' => 'required|string',
            'abstract_en' => 'required|string',
//            'body_uz' => 'required|string',
//            'body_ru' => 'required|string',
//            'body_en' => 'required|string',
            'books' => 'required|string',
            'file_uz' => 'required|mimes:doc,docx,pdf',
            'file_ru' => 'required|mimes:doc,docx,pdf',
            'file_en' => 'required|mimes:doc,docx,pdf',
            'authors' => 'required|string',
            'article_type_id' => 'required|integer'
        ]);

        $active_journal = $this->journalRepository->getActiveJournal();

        if(!$active_journal){
            return response()->json([
                'message_en' => 'There is no active journal',
                'message_ru' => 'Активный журнал не найден',
                'message_uz' => 'Faol jurnal topilmadi',
            ], 404);
        }

        $validate_data['journal_id'] = $active_journal['id'];

        $file = $request->file('file_uz')->getClientOriginalExtension();
        $microtime = md5(microtime());
        $file_name = $microtime."_uz.".$file;
        $request->file('file_uz')->move('articles/',$file_name);
        $validate_data['uz_file'] = 'articles/'.$file_name;

        $file = $request->file('file_ru')->getClientOriginalExtension();
        $file_name = $microtime."_ru.".$file;
        $request->file('file_ru')->move('articles/',$file_name);
        $validate_data['ru_file'] = 'articles/'.$file_name;

        $file = $request->file('file_en')->getClientOriginalExtension();
        $file_name = $microtime."_en.".$file;
        $request->file('file_en')->move('articles/',$file_name);
        $validate_data['en_file'] = 'articles/'.$file_name;

        $article = $this->articleRepository->createArticle($validate_data);

        $authors = json_decode($request->authors, true);

        foreach ($authors as $author) {
            $author['article_id'] = $article['id'];
            $this->authorRepository->createAuthor($author);
        }

        return response()->json([
            'message_en' => 'Article has been created successfully',
            'message_ru' => 'Статья успешно создана',
            'message_uz' => 'Maqola muvaffaqiyatli yaratildi',
            'data' => $article
        ]);
    }

    protected function replaceVariables($html, $data)
    {
        foreach ($data as $key => $value) {
            $html = str_replace($key, $value, $html);
        }
        return $html;
    }
}
