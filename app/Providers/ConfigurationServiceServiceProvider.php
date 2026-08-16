<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class ConfigurationServiceServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModels();
        $this->configureUrl();
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction()
        );
    }

    private function configureModels(): void
    {
        //		Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();
    }

    private function configureUrl(): void
    {
        URL::forceHttps($this->app->isProduction());
    }
}
