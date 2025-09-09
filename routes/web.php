<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BookController, SectionController};
use App\Http\Controllers\BookMemberController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('dashboard');

    Route::resource('books', BookController::class)->only(['index', 'show', 'store']);
    Route::post('/books', [BookController::class, 'store'])->name('books.store');

    Route::post('/books/{book}/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::patch('/books/{book}/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/books/{book}/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

    Route::post('/books/{book}/members', [BookMemberController::class, 'store'])->name('books.members.store');
    Route::delete('/books/{book}/members/{user}', [BookMemberController::class, 'destroy'])->name('books.members.destroy');

});



require __DIR__ . '/auth.php';
