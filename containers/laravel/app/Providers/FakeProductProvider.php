<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Fakers\ProductFaker;

class FakeProductProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(\Faker\Generator::class, function($app) {
            $faker = \Faker\Factory::create(config('app.faker_locale'));
            $faker->addProvider(new ProductFaker($faker));
            return $faker;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
