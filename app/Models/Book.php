<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model {
  protected $fillable = ['title','description'];

  public function sections(): HasMany {
    return $this->hasMany(Section::class)->whereNull('parent_id')->orderBy('position');
  }

  public function users(): BelongsToMany {
    return $this->belongsToMany(User::class)->withPivot(['role','can_create','can_edit'])->withTimestamps();
  }

  public function isAuthor(int $userId): bool {
    return $this->users()->wherePivot('role','author')->where('user_id',$userId)->exists();
  }
}