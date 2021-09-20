<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;
use MLL\GraphQLScalars\MixedScalar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $typeRegistry = app(TypeRegistry::class);
        $typeRegistry->register(new MixedScalar());
    }

    protected function registerWebSockets()
    {
        $this->app->singleton('websockets.router', function () {
            return new \App\WebSockets\Server\Router();
        });
    }
}
