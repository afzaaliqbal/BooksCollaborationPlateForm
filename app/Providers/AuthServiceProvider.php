<?php
namespace App\Providers;

use App\Models\Post;
use App\Models\Book;
use App\Models\Section;
use App\Policies\PostPolicy;
use App\Policies\BookPolicy;
use App\Policies\SectionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        Book::class => BookPolicy::class,
        Section::class => SectionPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
