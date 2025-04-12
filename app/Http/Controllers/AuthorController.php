<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Repositories\AuthorRepository;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct(
        protected AuthorRepository $authorRepository
    )
    {
    }

    public function index(){
        return response()->json([
            'status' => 'success',
            'message' => 'Author list',
            'data' => Author::all()
        ]);
    }

    public function create(){
        return view('author.create');
    }

    public function store(Request $request){
        $validated_data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'orcid' => 'nullable|string',
            'email' => 'nullable|email',
            'academic_degree_id' => 'nullable|integer',
            'institution' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $inserted_data = $this->authorRepository->createAuthor($validated_data);

        return response()->json([
            'status' => 'success',
            'message' => 'Author created successfully',
            'data' => $inserted_data
        ]);
    }

    public function edit($id){
        $author = Author::find($id);
        return view('author.edit', compact('author'));
    }

    public function update(Request $request, $id){
        $validated_data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'orcid' => 'nullable|string',
            'email' => 'nullable|email',
            'academic_degree_id' => 'nullable|integer',
            'institution' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $updated_data = $this->authorRepository->updateAuthor($validated_data, $id);

        return response()->json([
            'status' => 'success',
            'message' => 'Author updated successfully',
            'data' => $updated_data
        ]);
    }

    public function destroy($id){
        $author = Author::find($id);
        $author->delete();

        return redirect()->route('author.index')->with('success', 'Author deleted successfully');
    }

    public function search(Request $request){
        $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'institution' => 'nullable|string',
        ]);

        $query = Author::query();
        if ($request->has('first_name')) {
            $query->where('first_name', 'like', '%' . $request->first_name . '%');
        }
        if ($request->has('last_name')) {
            $query->where('last_name', 'like', '%' . $request->last_name . '%');
        }
        if ($request->has('institution')) {
            $query->where('institution', 'like', '%' . $request->institution . '%');
        }
        $authors = $query->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Author search results',
            'data' => $authors
        ]);
    }

    public function search_by_orcid(Request $request){
        $request->validate([
            'orcid' => 'required|string',
        ]);

        $author = Author::where('orcid', $request->orcid)->first();
        if ($author) {
            return response()->json([
                'status' => 'success',
                'message' => 'Author found',
                'data' => $author
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Author not found'
            ], 404);
        }
    }
}
