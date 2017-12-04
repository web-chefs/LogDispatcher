<?php

namespace WebChefs\LogRouter\Listeners;

// Framework
use Illuminate\Contracts\Queue\ShouldQueue;

class LogRouterAsync extends LogRouter
                  implements ShouldQueue
{

    const ASYNC = true;
}