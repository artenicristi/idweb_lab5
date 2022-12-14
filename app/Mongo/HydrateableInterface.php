<?php

namespace App\Mongo;

interface HydrateableInterface
{
    /**
     * Utilise pour remplir un document avec des donnees
     * @param array $data
     * @param HydrateableInterface|null $document
     * @return void
     */
    public static function hydrate($data, HydrateableInterface $document = null): HydrateableInterface;
}
