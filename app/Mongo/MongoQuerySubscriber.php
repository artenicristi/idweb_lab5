<?php

namespace App\Mongo;

use Barryvdh\Debugbar\Facades\Debugbar;
use MongoDB\Driver\Monitoring\CommandFailedEvent;
use MongoDB\Driver\Monitoring\CommandStartedEvent;
use MongoDB\Driver\Monitoring\CommandSubscriber;
use MongoDB\Driver\Monitoring\CommandSucceededEvent;

class MongoQuerySubscriber implements CommandSubscriber
{

    /**
     * @var MongoQueryCollector
     */
    private $collector;

    public function __construct()
    {
        $this->collector = Debugbar::getCollector('mongo');
    }

    public function commandStarted(CommandStartedEvent $event)
    {
        $this->collector->addQuery($event->getCommand(), $event->getOperationId());
    }

    public function commandSucceeded(CommandSucceededEvent $event)
    {
        $this->collector->addQueryDuration($event->getDurationMicros(), $event->getOperationId());
    }

    public function commandFailed(CommandFailedEvent $event)
    {
        // TODO: Implement commandFailed() method.
    }
}
