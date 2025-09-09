<?php
namespace App\Http\Controllers;

use App\Models\{Book, User};
use Illuminate\Http\Request;

class BookMemberController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $this->authorize('manageMembers', $book);

        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'can_edit' => ['sometimes', 'boolean'],
        ]);

        $user = User::whereEmail($data['email'])->first();

        // add as collaborator: edit allowed, create denied
        $book->users()->syncWithoutDetaching([
            $user->id => [
                'role' => 'collaborator',
                'can_create' => false,
                'can_edit' => $data['can_edit'] ?? true,
            ]
        ]);

        return back();
    }

    public function destroy(Book $book, User $user)
    {
        $this->authorize('manageMembers', $book);
        $book->users()->detach($user->id);
        return back();
    }
}