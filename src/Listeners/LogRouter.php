<?php

namespace WebChefs\LogRouter\Listeners;

// Framework
use Illuminate\Support\Arr;
use Illuminate\Console\Application;
use Illuminate\Log\Events\MessageLogged;

// Vendor
use Monolog\Logger as Monolog;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\HandlerInterface;

// Aliases
use Config;

class LogRouter
{

    const LEVELS = [
        'debug'     => Monolog::DEBUG,
        'info'      => Monolog::INFO,
        'notice'    => Monolog::NOTICE,
        'warning'   => Monolog::WARNING,
        'error'     => Monolog::ERROR,
        'critical'  => Monolog::CRITICAL,
        'alert'     => Monolog::ALERT,
        'emergency' => Monolog::EMERGENCY,
    ];

    const ASYNC = false;

    protected $monolog;
    protected $routes;

    /**
     * Setup that builds the logger and sets the handlers based on a config. We
     * dont setup in a constructor as monolog is note serializable.
     *
     * @return void
     */
    public function ensureSetup()
    {
        // Check if we already setup
        if (!is_null($this->monolog)) {
            return;
        }

        $this->makeLogger();
        $this->setHandlers();
    }

    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(MessageLogged $event)
    {
        $this->ensureSetup();
        $this->routeMessage($event);
    }

    protected function routeMessage(MessageLogged $event)
    {
        $this->monolog->{$event->level}($event->message . ' from =' . get_class($this), $event->context);
    }

    /**
     * Build the monolog object
     *
     * @return  void
     */
    protected function makeLogger()
    {
        $channel       = 'log_router.' . app()->environment();
        $this->monolog = new Monolog($channel);
    }

    /**
     * Set/push the handlers to the monolog object. A handler is only set to
     * active if it has at least one handler.
     *
     * @return  void
     */
    protected function setHandlers()
    {
        $routes = collect(Config::get('log_router.routes'))
                      ->transform(function($item) { return collect($item); })
                      ->filter(function($item) {
                          return $item->get('aSync', false) === static::ASYNC;
                      });

        $this->routes = $routes->map(function ($route, $name) {
            $class        = $route->get('handler');
            $filterLevels = $route->get('levels', array_keys(static::LEVELS));
            $params       = $route->get('params', []);

            $handler = $this->makeHandler($class, $filterLevels, $params);
            $this->monolog->pushHandler($handler);
            return $handler;
        });

        // $handler = new StreamHandler(storage_path('log/router/test.log'));
        // $handler = $this->handlerDefaults($handler);
        // $this->monolog->pushHandler($handler);
    }

    protected function makeHandler($handler, $levels, $params)
    {
        $handler = app()->makeWith($handler, $params);
        $handler = $this->handlerDefaults($handler);

        $levels  = $this->makeLevels($levels);
        return new FilterHandler($handler, $levels);
    }

    protected function makeLevels($levels)
    {
        return collect($levels)->transform(function($level) {
            return Arr::get(self::LEVELS, $level);
        })->all();
    }

    /**
     * By default set all handlers to the lowest log level as the dispatcher
     * will handle the level allocations.
     *
     * @param   HandlerInterface    $handler
     *
     * @return  HandlerInterface
     */
    protected function handlerDefaults(HandlerInterface $handler)
    {
        $handler->setLevel(Monolog::DEBUG);
        $handler->setBubble(true);
        return $handler;
    }

}