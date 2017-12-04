<?php

namespace WebChefs\LogRouter;

// Package
use WebChefs\LogRouter\Commands\TestLog;

// Framework
use Illuminate\Log\Events\MessageLogged;

// use Illuminate\Console\Application as Artisan;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
// use Illuminate\Database\Console\Migrations\MigrateCommand;
// use Illuminate\Database\Console\Migrations\RefreshCommand;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// // Aliases
// use Log;
// use Event;
// use Config;

class LogRouterServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    // protected $defer = false;

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Log\Events\MessageLogged' => [
            'WebChefs\LogRouter\Listeners\LogRouter',
            'WebChefs\LogRouter\Listeners\LogRouterAsync',
        ],
    ];

    /**
     * List of Commands defined by this package.
     *
     * @var array
     */
    protected $commands = [
        TestLog::class,
    ];

    /**
     * Register the service provider. Register is called before Boot.
     *
     * @return void
     */
    public function register()
    {
        // Register our commands with Artisan
        $this->commands($this->commands);

        // Log Dispatcher singleton
        // $this->app->singleton('Core\LogDispatcher\LogDispatcherContract', 'Core\LogDispatcher\LogDispatcher');

        // Make available our config data.
        $this->mergeConfigFrom(__DIR__ . '/Config.php', 'log_router');
    }

    /**
     * All services provides have been registered. Publish the plug-in
     * configuration and pass data to views.
     */
    public function boot()
    {
        parent::boot();

        // $events->listen('artisan.firing: migrate:*', function ($artisan, $input, $output) use ($events) {
        //     LogDispatcher::disable();
        //     $events->forget('illuminate.log');
        // });

        // $events->listen('artisan.firing: seed:*', function ($artisan, $input, $output) use ($events) {
        //     LogDispatcher::disable();
        //     $events->forget('illuminate.log');
        // });

        $this->publishes([
            __DIR__ . '/Config.php' => config_path('log_router.php')
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    // public function provides()
    // {
    //     return ['Core\LogDispatcher\LogDispatcherContract', 'LogDispatcherContract'];
    // }

    /*
     |--------------------------------------------------------------------------
     | Events List
     |--------------------------------------------------------------------------
     |
     | For reference purposes we list all Laravel Events based on some of the
     | following references.
     |
     | Ref:
     | - http://stackoverflow.com/questions/13059744/where-can-i-get-a-complete-list-of-laravel-events-fired-by-the-core-libaries
     | - https://laracasts.com/discuss/channels/general-discussion/where-can-i-get-a-complete-list-of-laravel-5-events?page=1
     |
     |--------------------------------------------------------------------------
     |
     | laravel.log
     | laravel.query
     | laravel.resolving
     | laravel.composing: {viewname}
     | laravel.started: {bundlename}
     | laravel.controller.factory
     | laravel.config.loader
     | laravel.language.loader
     | laravel.view.loader
     | laravel.view.engine
     | laravel.done
     |
     | view.filter
     |
     | eloquent.saving
     | eloquent.updated
     | eloquent.created
     | eloquent.saved
     | eloquent.deleting
     | eloquent.deleted
     |
     | $this->events->fire('auth.attempt', $payload);
     | $this->events->fire('auth.login', [$user, $remember]);
     | $this->events->fire('auth.logout', [$user]);
     | $this->events->fire('cache.'.$event, $payload);
     | $this->laravel['events']->fire('cache:clearing', [$storeName]);
     | $this->laravel['events']->fire('cache:cleared', [$storeName]);
     | $events->fire('artisan.start', [$this]);
     | $this->events->fire('illuminate.query', array($query, $bindings, $time, $this->getName()));
     | $this->events->fire('connection.'.$this->getName().'.'.$event, $this);
     | $this['events']->fire('bootstrapping: '.$bootstrapper, [$this]);
     | $this['events']->fire('bootstrapped: '.$bootstrapper, [$this]);
     | $this['events']->fire('locale.changed', array($locale));
     | $this['events']->fire($class = get_class($provider), array($provider));  //after provider registered.
     | $this->app['events']->fire('kernel.handled', [$request, $response]);
     | $this->dispatcher->fire('illuminate.log', compact('level', 'message', 'context'));
     | $this->events->fire('mailer.sending', array($message));
     | $this->events->fire('illuminate.queue.failed', array($connection, $job, $data));
     | $this->events->fire('illuminate.queue.stopping');
     | $this->events->fire('router.matched', [$route, $request]);
     | $this->events->fire('composing: '.$view->getName(), array($view));
     | $this->events->fire('creating: '.$view->getName(), array($view));
     */
}