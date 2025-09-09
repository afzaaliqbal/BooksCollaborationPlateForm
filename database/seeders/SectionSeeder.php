<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Book, Section, User};

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        // get or create a demo user
        $user = User::first() ?? User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
        ]);

        // create a demo book
        $book = Book::firstOrCreate(
            ['title' => 'Demo Book'],
            ['description' => 'This is a seeded demo book']
        );

        // attach user as author
        $book->users()->syncWithoutDetaching([
            $user->id => ['role' => 'author', 'can_create' => true, 'can_edit' => true],
        ]);

        // top-level section
        $intro = Section::firstOrCreate(
            ['book_id' => $book->id, 'title' => 'Introduction'],
            ['content' => 'This is the introduction']
        );

        // subsections
        $platform = Section::firstOrCreate(
            ['book_id' => $book->id, 'parent_id' => $intro->id, 'title' => 'Intro to Platform'],
            ['content' => 'Platform details...']
        );

        Section::firstOrCreate(
            ['book_id' => $book->id, 'parent_id' => $platform->id, 'title' => 'Menu Bar'],
            ['content' => 'Description of Menu Bar']
        );

        Section::firstOrCreate(
            ['book_id' => $book->id, 'parent_id' => $platform->id, 'title' => 'Writing'],
            ['content' => 'Writing details...']
        );

        $advanced = Section::firstOrCreate(
            ['book_id' => $book->id, 'title' => 'Advanced Topics'],
            ['content' => 'This is an advanced section']
        );

        Section::firstOrCreate(
            ['book_id' => $book->id, 'parent_id' => $advanced->id, 'title' => 'Collaboration'],
            ['content' => 'Details on collaboration']
        );
    }
}