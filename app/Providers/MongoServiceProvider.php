<?php

namespace App\Providers;

use App\Mongo\Document;
use App\Mongo\MongoQueryCollector;
use App\Mongo\MongoQuerySubscriber;
use App\Services\MongoService;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\ServiceProvider;
use function MongoDB\Driver\Monitoring\addSubscriber;

class MongoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Document::setMongoService($this->app[MongoService::class]);

        if (config('app.debug')) {
            Debugbar::addCollector(new MongoQueryCollector());
//            addSubscriber(new MongoQuerySubscriber());
        }
    }

    public function register()
    {
        $this->app->singleton(MongoService::class, function () {
            $config = config('database');

            return new MongoService($config);
        });
    }
}
