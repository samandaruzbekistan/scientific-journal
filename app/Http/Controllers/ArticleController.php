<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use App\Repositories\AuthorRepository;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepository $articleRepository, protected AuthorRepository $authorRepository)
    {
    }

    public function store(Request $request){
        $request->validate([
            'title_uz' => 'required',
            'title_ru' => 'required',
            'title_en' => 'required',
            'keywords_uz' => 'required',
            'keywords_ru' => 'required',
            'keywords_en' => 'required',
            'abstract_uz' => 'required',
            'abstract_ru' => 'required',
            'abstract_en' => 'required',
            'body_uz' => 'required',
            'body_ru' => 'required',
            'body_en' => 'required',
            'books' => 'required',
            'authors' => 'required',
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

        $authors = "";

        $author_name = "";

        foreach ($request->authors as $author) {
            if($author['roles'] == 'author'){
                $author_name = $author['first_name']." ".$author['last_name'];
            }
            $author['article_id'] = $article['id'];
            $authors = $authors." ".$author['first_name']." ".$author['last_name'];
            $this->authorRepository->createAuthor($author);
        }

        $wordDocumentUz = IOFactory::load("template_uz.docx");
        $htmlWriterUz = IOFactory::createWriter($wordDocumentUz, 'HTML');

        ob_start(); // HTML ni olish uchun buffer ochamiz
        $htmlWriterUz->save('php://output');
        $htmlUz = ob_get_clean();

        $wordDocumentRu = IOFactory::load("template_ru.docx");
        $htmlWriterRu = IOFactory::createWriter($wordDocumentRu, 'HTML');
        ob_start(); // HTML ni olish uchun buffer ochamiz
        $htmlWriterRu->save('php://output');
        $htmlRu = ob_get_clean();

        $wordDocumentEn = IOFactory::load("template_en.docx");
        $htmlWriterEn = IOFactory::createWriter($wordDocumentEn, 'HTML');
        ob_start(); // HTML ni olish uchun buffer ochamiz
        $htmlWriterEn->save('php://output');
        $htmlEn = ob_get_clean();

        $data_uz = [
            '${title_uz}' => $request->title_uz,
            '${keywords_uz}' => $request->keywords_uz,
            '${abstract_uz}' => $request->abstract_uz,
            '${body_uz}' => $request->body_uz,
            '${books}' => $request->books,
            '${authors}' => $authors,
            '${author}' => $author_name,
        ];

        $data_ru = [
            '${title_ru}' => $request->title_ru,
            '${keywords_ru}' => $request->keywords_ru,
            '${abstract_ru}' => $request->abstract_ru,
            '${body_ru}' => $request->body_ru,
            '${books}' => $request->books,
            '${authors}' => $authors,
            '${author}' => $author_name,
        ];

        $data_en = [
            '${title_en}' => $request->title_en,
            '${keywords_en}' => $request->keywords_en,
            '${abstract_en}' => $request->abstract_en,
            '${body_en}' => $request->body_en,
            '${books}' => $request->books,
            '${authors}' => $authors,
            '${author}' => $author_name,
        ];



        $updatedHtmlUz = $this->replaceVariables($htmlUz, $data_uz);
        $updatedHtmlRu = $this->replaceVariables($htmlRu, $data_ru);
        $updatedHtmlEn = $this->replaceVariables($htmlEn, $data_en);

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->AddPage();
        $pdf->writeHTML($updatedHtmlUz, true, false, true, false, '');
        $pdf->lastPage();
        $pdf->Output(public_path("articles/uz_{$article['id']}".'.pdf'), 'F');

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->AddPage();
        $pdf->writeHTML($updatedHtmlRu, true, false, true, false, '');
        $pdf->lastPage();
        $pdf->Output(public_path("articles/ru_{$article['id']}".'.pdf'), 'F');

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->AddPage();
        $pdf->writeHTML($updatedHtmlEn, true, false, true, false, '');
        $pdf->lastPage();
        $pdf->Output(public_path("articles/en_{$article['id']}".'.pdf'), 'F');

        $this->articleRepository->updateArticle([
            'uz_file' => "articles/uz_{$article['id']}".'.pdf',
            'ru_file' => "articles/ru_{$article['id']}".'.pdf',
            'en_file' => "articles/en_{$article['id']}".'.pdf',
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
