<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NutritionalValueService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * This method is used to bind services into the Laravel service container.
     * Here, we register the NutritionalValueService.
     */
    public function register()
    {
        // Registra o NutritionalValueService no container de serviços
        $this->app->singleton(NutritionalValueService::class, function ($app) {
            return new NutritionalValueService();
        });
    }

    /**
     * Bootstrap services.
     *
     * This method is used to initialize services after all of them are registered.
     */
    public function boot()
    {
        // Configurações adicionais podem ser feitas aqui, caso necessário
    }
}
