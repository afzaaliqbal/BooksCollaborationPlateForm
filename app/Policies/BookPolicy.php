<?php
namespace App\Policies;
use App\Models\{Book,User};

class BookPolicy {
  public function view(User $u, Book $b): bool {
    return $b->users()->where('user_id',$u->id)->exists();
  }
  public function update(User $u, Book $b): bool {
    return $b->users()->where('user_id',$u->id)->wherePivot('can_edit',true)->exists();
  }
  public function manageMembers(User $u, Book $b): bool {
    return $b->isAuthor($u->id);
  }
}