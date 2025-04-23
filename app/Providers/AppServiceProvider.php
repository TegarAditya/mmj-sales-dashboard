<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
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
        Blueprint::macro('userAuditable', function () {
            /** @var Blueprint $this */
            $this->foreignIdFor(User::class, 'created_by')->nullable()->constrained('users');
            $this->foreignIdFor(User::class, 'updated_by')->nullable()->constrained('users');
            $this->foreignIdFor(User::class, 'deleted_by')->nullable()->constrained('users');
        });
    }
}
