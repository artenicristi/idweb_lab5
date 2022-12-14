<?php

namespace App\Documents;

use App\Mongo\Document;
use App\Mongo\HydrateableInterface;

class Commentaire extends Document
{
    public static $collectionName = 'Commentaire';

    public static function hydrate($data, HydrateableInterface $document = null): HydrateableInterface
    {
        // TODO: Implement hydrate() method.
    }
}
