<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('books', function (Blueprint $t) {
      $t->id(); $t->string('title'); $t->text('description')->nullable(); $t->timestamps();
    });

    // membership + role per book
    Schema::create('book_user', function (Blueprint $t) {
      $t->id();
      $t->foreignId('book_id')->constrained()->cascadeOnDelete();
      $t->foreignId('user_id')->constrained()->cascadeOnDelete();
      $t->enum('role', ['author','collaborator'])->index();
      $t->boolean('can_create')->default(true);
      $t->boolean('can_edit')->default(true);
      $t->timestamps();
      $t->unique(['book_id','user_id']);
    });

    Schema::create('sections', function (Blueprint $t) {
      $t->id();
      $t->foreignId('book_id')->constrained()->cascadeOnDelete();
      $t->foreignId('parent_id')->nullable()->constrained('sections')->cascadeOnDelete();
      $t->string('title');
      $t->longText('content')->nullable();
      $t->unsignedInteger('position')->default(0);
      $t->timestamps();
      $t->index(['book_id','parent_id']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('sections');
    Schema::dropIfExists('book_user');
    Schema::dropIfExists('books');
  }
};