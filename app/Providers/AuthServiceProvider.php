<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\InformationComplementaire;
use App\Models\Post;
use App\Policies\InformationComplementairePolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        InformationComplementaire::class => InformationComplementairePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
