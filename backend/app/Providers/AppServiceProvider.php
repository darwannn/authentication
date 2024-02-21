<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::$accessTokenAuthenticationCallback = function ($accessToken, $isValid) {
            $is_unused = !$accessToken->last_used_at || $accessToken->last_used_at->gte(now()->subHours(1));
            if (!$is_unused) {
                $accessToken->delete();
            }
            return  $is_unused;
        };

        //can get other param like $post
        //https: //www.youtube.com/watch?v=wK-dtZyN8p0
        Gate::define('admin-only', function ($user) {

            if ($user->role == "admin") {
                return true;
            }
            return false;
        });
    }
}
