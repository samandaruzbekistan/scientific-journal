<?php

namespace App\Http\Controllers;

use App\Repositories\ArticleRepository;
use App\Repositories\EditorialRepository;
use App\Repositories\EditorialsTeamRepository;
use App\Repositories\PublishVoteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class EditorialController extends Controller
{
    public function __construct(
        protected EditorialRepository $editorialRepository,
        protected ArticleRepository $articleRepository,
        protected EditorialsTeamRepository $editorialsTeamRepository,
        protected PublishVoteRepository $publishVoteRepository
    )
    {
    }

    public function index()
    {
        return response()->json($this->editorialRepository->getAll());
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $editorial = $this->editorialRepository->getByEmail($request->email);

        if(!$editorial){
            return response()->json([
                'message' => 'Email yoki parol xato',
            ], 404);
        }

        if(!Hash::check($request->password, $editorial->password)){
            return response()->json([
                'message' => 'Email yoki parol xato',
            ], 404);
        }

        $token = $editorial->createToken('editorial')->plainTextToken;

        return response()->json([
            'token' => $token,
            'editorial' => $editorial,
        ]);
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

//    Article control
    public function get_review_articles(Request $request){
        $request->validate([
            'article_type_id' => 'required|integer|exists:article_types,id',
        ]);

        $articles = $this->articleRepository->getArticlesByStatusAndType('review', $request->article_type_id);
        return response()->json($articles);
    }

    public function update_article_status(Request $request){
        $request->validate([
            'article_id' => 'required|integer',
            'status' => 'required|string',
        ]);

        $article = $this->articleRepository->getArticle($request->article_id);

        if(!$article){
            return response()->json([
                'message' => 'Maqola topilmadi',
            ], 404);
        }

        if($article->status != 'editorial'){
            return response()->json([
                'message' => 'Maqola tekshirishda emas',
            ], 400);
        }

        $this->articleRepository->updateArticle([
            'status' => $request->status,
        ], $request->article_id);

        return response()->json([
            'message' => 'Maqola muvaffaqiyatli taxrirlashga yuborildi',
        ]);
    }

    public function get_article($id){
        $article = $this->articleRepository->getArticle($id);

        if(!$article){
            return response()->json([
                'message' => 'Maqola topilmadi',
            ], 404);
        }

        return response()->json($article);
    }

    public function send_to_editorial(Request $request){
        $validate_data = $request->validate([
            'article_id' => 'required|integer',
        ]);

        $article = $this->articleRepository->getArticle($validate_data['article_id']);

        if(!$article){
            return response()->json([
                'message' => 'Maqola topilmadi',
            ], 404);
        }

        if($article['status'] != 'review'){
            return response()->json([
                'message' => 'Maqola tekshirishda emas',
            ], 400);
        }

        $this->articleRepository->updateArticle([
            'status' => 'editorial'
        ], $validate_data['article_id']);

        $team = $this->editorialsTeamRepository->getByTypeId($article->article_type_id);

        $ids_array = json_decode($team->json_data);

        foreach ($ids_array as $id){
            $this->publishVoteRepository->createPublishVote([
                'editorial_id' => $id,
                'article_id' => $article->id,
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'message' => 'Maqola muvaffaqiyatli taxrirlashga yuborildi',
        ]);
    }

    public function get_publish_votes(Request $request){
        $request->validate([
            'editorial_id' => 'required|integer',
        ]);

        $votes = $this->publishVoteRepository->getByEditorialId($request->editorial_id);

        return response()->json($votes);
    }

    public function vote(Request $request){
        $request->validate([
            'vote' => 'required|string|in:1,0',
            'editorial_id' => 'required|integer',
            'article_id' => 'required|integer',
        ]);

        $vote = $this->publishVoteRepository->getVoteByArticleAndEditorial($request->article_id, $request->editorial_id);

        if(!$vote){
            return response()->json([
                'message' => 'Ovoz topilmadi',
            ], 404);
        }

        if($vote->status != 'pending'){
            return response()->json([
                'message' => 'Ovoz berish mumkin emas',
            ], 404);
        }

        $this->publishVoteRepository->updatePublishVote([
            'vote' => $request->vote,
            'status' => 'voted',
        ], $vote->id);

        $votes = $this->publishVoteRepository->getPublishVotesByArticleId($request->article_id);

        foreach ($votes as $vote){
            if($vote->status == 'pending'){
                return response()->json([
                    'message' => 'Ovoz muvaffaqiyatli berildi',
                ]);
            }

            if($vote->vote == 0){
                $this->articleRepository->updateArticle([
                    'status' => 'rejected',
                ], $request->article_id);

                return response()->json([
                    'message' => 'Ovoz muvaffaqiyatli berildi',
                ]);
            }
        }

        $this->articleRepository->updateArticle([
            'status' => 'published',
        ], $request->article_id);

        return response()->json([
            'message' => 'Ovoz muvaffaqiyatli berildi',
        ]);
    }
}
