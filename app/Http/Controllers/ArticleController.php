<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use App\Repositories\ArticleTypeRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\JournalRepository;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleRepository $articleRepository,
        protected AuthorRepository $authorRepository,
        protected JournalRepository $journalRepository,
        protected InvoiceRepository $invoiceRepository,
        protected ArticleTypeRepository $articleTypeRepository,

    )
    {
    }

    public function show($id){
        $article = $this->articleRepository->getArticle($id);

        if(!$article){
            return response()->json([
                'message_en' => 'Article not found',
                'message_ru' => 'Статья не найдена',
                'message_uz' => 'Maqola topilmadi',
            ], 404);
        }

        return response()->json($article);
    }

    public function get_articles($user_id){
        $articles = $this->articleRepository->getArticlesByUserId($user_id);

        return response()->json($articles);
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
            'article_type_id' => 'required|integer',
            'user_id' => 'required|integer',
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

        $this->journalRepository->increment_article_count($active_journal['id']);

        $authors = json_decode($request->authors, true);

        foreach ($authors as $author) {
            $author['article_id'] = $article['id'];
            $this->authorRepository->createAuthor($author);
        }

        $article_type = $this->articleTypeRepository->getArticleType($validate_data['article_type_id']);

        $invoice_number = $this->invoiceRepository->generateInvoiceNumber();

        $invoice_data = [
            'invoice_number' => $invoice_number,
            'status' => 'pending',
            'user_id' => $request->user_id,
            'journal_id' => $active_journal['id'],
            'article_id' => $article['id'],
            'article_type_id' => $validate_data['article_type_id'],
            'price' => $article_type['price']
        ];

        $invoice = $this->invoiceRepository->createInvoice($invoice_data);

        return response()->json([
            'message_en' => 'Article has been created successfully',
            'message_ru' => 'Статья успешно создана',
            'message_uz' => 'Maqola muvaffaqiyatli yaratildi',
            'invoice' => $invoice,
            'data' => $article
        ]);
    }

    public function send_to_review(Request $request)
    {
        $validate_data = $request->validate([
            'article_id' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $article = $this->articleRepository->getArticle($validate_data['article_id']);

        if(!$article){
            return response()->json([
                'message_en' => 'Article not found',
                'message_ru' => 'Статья не найдена',
                'message_uz' => 'Maqola topilmadi',
            ], 404);
        }

        if($article['status'] != 'pending'){
            return response()->json([
                'message_en' => 'Article is not pending',
                'message_ru' => 'Статья не находится в ожидании',
                'message_uz' => 'Maqola kutilmoqda emas',
            ], 400);
        }

        if($article['user_id'] != $validate_data['user_id']){
            return response()->json([
                'message_en' => 'You are not allowed to send this article to review',
                'message_ru' => 'Вы не можете отправить эту статью на рассмотрение',
                'message_uz' => 'Bu maqolani ko`rib chiqishga ruxsat etilmadingiz',
            ], 400);
        }

        $invoice = $this->invoiceRepository->getInvoiceByArticleId($validate_data['article_id']);

        if(!$invoice){
            return response()->json([
                'message_en' => 'Invoice not found',
                'message_ru' => 'Счет не найден',
                'message_uz' => 'Hisob-faktura topilmadi',
            ], 404);
        }

//        if($invoice['status'] != 'paid'){
//            return response()->json([
//                'message_en' => 'Invoice is not paid',
//                'message_ru' => 'Счет не оплачен',
//                'message_uz' => 'Hisob-faktura to`lanmagan',
//            ], 400);
//        }

        $this->articleRepository->updateArticle(['status' => 'review'], $validate_data['article_id']);

        return response()->json([
            'message_en' => 'Article has been sent to review',
            'message_ru' => 'Статья отправлена на рассмотрение',
            'message_uz' => 'Maqola ko`rib chiqilish uchun yuborildi',
        ]);
    }
}
