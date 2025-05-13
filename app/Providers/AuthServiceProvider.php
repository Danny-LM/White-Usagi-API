<?php

namespace App\Providers;

use App\Models\Anime;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Studio;
use App\Models\User;
use App\Policies\AnimePolicy;
use App\Policies\EpisodePolicy;
use App\Policies\GenrePolicy;
use App\Policies\StudioPolicy;
use App\Policies\TokenPolicy;
use App\Policies\UserPolicy;
use Doctrine\Common\Lexer\Token;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Anime::class => AnimePolicy::class,
        Episode::class => EpisodePolicy::class,
        Genre::class => GenrePolicy::class,
        Studio::class => StudioPolicy::class,
        Token::class => TokenPolicy::class,
        User::class => UserPolicy::class,
        PersonalAccessToken::class => TokenPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();
    }
}
