<?php
namespace App\Http\Controllers;

use App\Models\{Book, Section};
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = $request->user()->books()->select('books.id', 'title', 'description')->get();
        return Inertia::render('Books/Index', ['books' => $books]);
    }

    public function show(Book $book)
    {
        $this->authorize('view', $book);
        $is_author = $book->isAuthor(auth()->id());
        // collaborators only
        $collaborators = $book->users()
            ->wherePivot('role', 'collaborator')
            ->get(['users.id', 'users.name', 'users.email']);

        // cache sections tree per book
        $tree = cache()->remember("book:{$book->id}:tree", 60, function () use ($book) {
            $load = null;                           // declare first
            $load = function ($q) use (&$load) {    // <-- bind by reference
                $q->with(['children' => $load])
                    ->orderBy('position');
            };

            return Section::with(['children' => $load])
                ->where('book_id', $book->id)
                ->whereNull('parent_id')
                ->orderBy('position')
                ->get();
        });

        return Inertia::render('Books/Show', [
            'book' => $book,
            'tree' => $tree,
            'is_author' => $is_author,
            'collaborators' => $collaborators,
        ]);
    }

    public function store(Request $request)
    {
        $book = Book::create($request->validate(['title' => 'required', 'description' => 'nullable']));
        $book->users()->attach($request->user()->id, ['role' => 'author', 'can_create' => 1, 'can_edit' => 1]);
        return redirect()->route('books.show', $book);
    }
}