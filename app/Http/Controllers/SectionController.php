<?php
namespace App\Http\Controllers;

use App\Models\{Book, Section};
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $sections = new Section($request->validate([
            'parent_id' => 'nullable|exists:sections,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable',
            'position' => 'nullable|integer'
        ]));
        $sections->book()->associate($book);
        $this->authorize('create', $sections);
        // if ($request->user()->cannot('create', $sections)) {
        //     return back()->with('error', 'You do not have permission to create a section in this book.');
        // }
        $sections->save();
        cache()->forget("book:{$book->id}:tree");
        return back();
    }

    public function update(Request $request, Book $book, Section $section)
    {
        $this->authorize('update', $section);
        $section->update($request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable',
            'position' => 'integer'
        ]));
        cache()->forget("book:{$book->id}:tree");
        return back();
    }

    public function destroy(Book $book, Section $section)
    {
        $this->authorize('delete', $section);
        $section->delete();
        cache()->forget("book:{$book->id}:tree");
        return back();
    }
}