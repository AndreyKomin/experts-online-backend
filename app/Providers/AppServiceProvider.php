<?php

namespace App\Providers;

use App\Contracts\IRepository;
use App\Contracts\IRepositoryFactory;
use App\Contracts\IServiceManager;
use App\Contracts\ITransformer;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\UsersController;
use App\Models\Repositories\UsersRepository;
use App\Models\User;
use App\Repositories\RepositoryFactory;
use App\Services\ServiceManager;
use App\Transformers\BaseTransformer;
use Dingo\Api\Exception\ValidationHttpException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Reliese\Coders\CodersServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(CodersServiceProvider::class);
        }
        $this->app->bind(ITransformer::class, BaseTransformer::class);
        $this->app->when(UsersController::class)
            ->needs(IRepository::class)
            ->give(UsersRepository::class);
        $this->app->bind(ClientInterface::class, Client::class);

        app('Dingo\Api\Exception\Handler')->register(function (ValidationException $exception) {
            throw new ValidationHttpException($exception->errors());
        });

        $this->app->singleton(IRepositoryFactory::class, RepositoryFactory::class);

        $this->app->when(AuthController::class)->needs(IServiceManager::class)->give(function (Application $application
        ) {
            return new ServiceManager(
                User::class,
                $application->make(IRepositoryFactory::class),
                $application->make(Factory::class),
                $application->make(ConnectionInterface::class)
            );
        });


    }
}
