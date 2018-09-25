<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mailer', function ($app) {
            $app->configure('mail');
            return $app->loadComponent('mail', \Illuminate\Mail\MailServiceProvider::class, 'mailer');
        });
    }
}
