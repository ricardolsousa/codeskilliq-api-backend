<?php

namespace App\Http\Controllers\API;

use App\Models\Language;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');

        if ($id) {
            $question = Question::find($id);

            if (!$question) {
                return response()->json(['error' => 'Question Not Found'], 404);
            }

            return response()->json($question);
        }

        $pageSize = $request->input('page_size', 10); // Page size inserted by user or 10

        $questions = Question::paginate($pageSize);

        return response()->json([
            'data' => $questions->items(), // Current Data
            'meta' => [ // Meta data for current page, amount of data and other stuff
                'current_page' => $questions->currentPage(),
                'from' => $questions->firstItem(),
                'to' => $questions->lastItem(),
                'page_size' => $questions->perPage(),
                'total' => $questions->total(),
                'last_page' => $questions->lastPage(),
            ],
            'links' => [ // Links to next and previous pages
                'next_page' => $questions->nextPageUrl(),
                'prev_page' => $questions->previousPageUrl(),
            ],
        ]);
    }

    public function getQuestionsByLanguage($languageId, Request $request)
    {
        // Verifique se a linguagem existe
        $language = Language::find($languageId);

        if (!$language) {
            return response()->json(['error' => 'Language not found'], 404);
        }

        $pageSize = $request->input('page_size', 10); // Page size inserted by user or 10

        // Recupere as questões relacionadas a essa linguagem
        $questions = $language->questions()->paginate($pageSize);

        return response()->json([
            'data' => $questions->items(), // Current Data
            'meta' => [ // Meta data for current page, amount of data and other stuff
                'current_page' => $questions->currentPage(),
                'from' => $questions->firstItem(),
                'to' => $questions->lastItem(),
                'page_size' => $questions->perPage(),
                'total' => $questions->total(),
                'last_page' => $questions->lastPage(),
            ],
            'links' => [ // Links to next and previous pages
                'next_page' => $questions->nextPageUrl(),
                'prev_page' => $questions->previousPageUrl(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
            'language_id' => [
                'required',
                Rule::exists('languages', 'id'),
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $question = Question::create($request->all());

        return response()->json($question, 201);
    }

    public function show(Question $question)
    {
        return response()->json($question);
    }

    public function update(Request $request, Question $question)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
            'language_id' => [
                'required',
                Rule::exists('languages', 'id'),
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id'),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $question->update($request->all());

        return response()->json($question);
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return response()->json(['message' => 'Question removed with success']);
    }
}
