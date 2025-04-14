<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerPolicies();

    Gate::define('inicio', function ($user) {
        return $user->hasPermissionTo('inicio'); // O usa lógica personalizada
    });
    Gate::define('filtros', function ($user) {
        return $user->hasAnyRole(['administrador', 'soporte']); // Ajusta los roles según necesites
    });

    }
}
