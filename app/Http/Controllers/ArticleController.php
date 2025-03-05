<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use App\Repositories\AuthorRepository;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepository $articleRepository, protected AuthorRepository $authorRepository)
    {
    }

    public function store(Request $request){
        $request->validate([
            'journal_id' => 'required|numeric',
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
            'authors.*.first_name' => 'required',
            'authors.*.last_name' => 'required',
            'authors.*.orcid' => 'required',
            'authors.*.roles' => 'required', // Add this line
            'authors.*.email' => 'required',
            'authors.*.academic_degree' => 'required',
            'authors.*.institution' => 'required',
            'authors.*.country' => 'required',
        ]);

        $article = $this->articleRepository->createArticle($request->all());

        foreach ($request->authors as $author) {
            $author['article_id'] = $article['id'];
            $this->authorRepository->createAuthor($author);
        }

        $request->file('file_uz')->move('articles', $article['id'].'_uz.'.$request->file('file_uz')->extension());
        $request->file('file_ru')->move('articles', $article['id'].'_ru.'.$request->file('file_ru')->extension());
        $request->file('file_en')->move('articles', $article['id'].'_en.'.$request->file('file_en')->extension());

        $this->articleRepository->updateArticle([
            'uz_file' => "articles/".$article['id'].'_uz.'.$request->file('file_uz')->extension(),
            'ru_file' => "articles/".$article['id'].'_ru.'.$request->file('file_ru')->extension(),
            'en_file' => "articles/".$article['id'].'_en.'.$request->file('file_en')->extension(),
        ], $article['id']);

        $article = $this->articleRepository->getArticle($article['id']);

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
