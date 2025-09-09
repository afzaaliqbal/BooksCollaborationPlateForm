<?php
namespace App\Policies;
use App\Models\{Section,User};

class SectionPolicy {
  public function create(User $u, Section $s): bool {
    return $s->book->users()->where('user_id',$u->id)->wherePivot('can_create',true)->exists();
  }
  public function update(User $u, Section $s): bool {
    return $s->book->users()->where('user_id',$u->id)->wherePivot('can_edit',true)->exists();
  }
  public function delete(User $u, Section $s): bool { return $this->update($u,$s); }
}