<?php

namespace App\Mongo;

class AggregationBuilder
{
    /**
     * Aggregation pipeline
     * @var array
     */
    private $pipeline = [];

    public function project($projection)
    {
        $this->addStage(['$project' => $projection]);

        return $this;
    }

    private function addStage(array $stage)
    {
    }
}
