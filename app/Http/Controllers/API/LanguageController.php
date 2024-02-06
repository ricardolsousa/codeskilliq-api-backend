<?php

namespace App\Http\Controllers\API;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');

        if ($id) {
            $language = Language::find($id);

            if (!$language) {
                return response()->json(['error' => 'Language Not Found'], 404);
            }

            return response()->json($language);
        }

        if ($name) {
            $language = Language::where('name', $name)->first();

            if (!$language) {
                return response()->json(['error' => 'Language Not Found'], 404);
            }

            return response()->json($language);
        }

        $pageSize = $request->input('page_size', 10); // Page size inserted by user or 10

        $languages = Language::paginate($pageSize);

        return response()->json([
            'data' => $languages->items(), // Current Data
            'meta' => [ // Meta data for current page, amount of data and other stuff
                'current_page' => $languages->currentPage(),
                'from' => $languages->firstItem(),
                'to' => $languages->lastItem(),
                'page_size' => $languages->perPage(),
                'total' => $languages->total(),
                'last_page' => $languages->lastPage(),
            ],
            'links' => [ // Links to next and previous pages
                'next_page' => $languages->nextPageUrl(),
                'prev_page' => $languages->previousPageUrl(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:languages,name'
        ], $messages = [
            'unique' => 'Name of language already in use'
        ]);

        if ($validator->fails()) {
            return response()->json($messages, 400);
        }

        $language = Language::create([
            'name' => $request->input('name'),
        ]);

        return response()->json($language, 201);
    }

    public function show(Language $language)
    {
        return response()->json($language);
    }

    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name' => ['required', Rule::unique('languages', 'name')->ignore($language->id)],
        ]);

        $language->update([
            'name' => $request->input('name'),
        ]);

        return response()->json($language);
    }

    public function destroy(Language $language)
    {
        $language->delete();

        return response()->json(['message' => 'Language removed with success']);
    }
}
