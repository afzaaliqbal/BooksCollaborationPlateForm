<?php
namespace Database\Seeders;

use App\Models\{Book,Section,User};
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder {
  public function run(): void {
    $user = User::first() ?? User::factory()->create(['email'=>'demo@demo.com']);
    $book = Book::create(['title'=>'Intro to Platform','description'=>'Demo book']);
    $book->users()->attach($user->id, ['role'=>'author','can_create'=>1,'can_edit'=>1]);

    $intro = Section::create(['book_id'=>$book->id,'title'=>'Introduction']);
    Section::create(['book_id'=>$book->id,'parent_id'=>$intro->id,'title'=>'Intro to Platform (Menu Bar)']);
    Section::create(['book_id'=>$book->id,'parent_id'=>$intro->id,'title'=>'Intro to Writing']);
  }
}