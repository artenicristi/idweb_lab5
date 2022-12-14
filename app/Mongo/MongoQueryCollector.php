<?php

namespace App\Mongo;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\DataCollectorInterface;
use DebugBar\DataCollector\Renderable;

/**
 * Collector for Mongo Queries.
 */
class MongoQueryCollector extends DataCollector implements DataCollectorInterface, Renderable
{
    protected $queries = [];

    protected $duration = 0;

    public function addQuery($query, $id)
    {
        $query = json_decode(json_encode($query), true);
        unset($query['lsid'], $query['cursor']);
        $stackTrace = debug_backtrace();
        $stackTrace = array_map(function($item) {
            if (isset($item['class'], $item['function'])) {
                return $item['class'] . ($item['type'] ?? '::') . $item['function'];
            } elseif (isset($item['file'], $item['line'])) {
                return $item['file'] . ':' . $item['line'];
            }

            return null;
        }, $stackTrace);

        $query['__trace'] = $stackTrace;

        $this->queries[$id] = [
            'duration' => null,
            'command' => $query,
        ];
    }

    public function addQueryDuration($duration, $id)
    {
        $this->queries[$id]['duration'] = $duration;
        $this->duration += $duration;
    }

    public function collect()
    {
        $data = [
            count($this->queries) . ' queries were executed. Total time' => ($this->duration * 0.001) . ' ms'
        ];

        $idx = 0;
        foreach ($this->queries as $query) {
            $key = ($idx++) . ' (' . ($query['duration'] * 0.001) . ' ms';
            $data[$key] = DataCollector::getDefaultVarDumper()->renderVar($query['command']);
        }

        return ['data' => $data, 'count' => count($this->queries)];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'mongo';
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgets()
    {
        return [
            "mongo" => [
                "icon" => "cubes",
                "widget" => "PhpDebugBar.Widgets.HtmlVariableListWidget",
                "map" => "mongo.data",
                "default" => "{}"
            ],
            'mongo:badge' => [
                'map' => 'mongo.count',
                'default' => 0
            ]
        ];
    }
}
