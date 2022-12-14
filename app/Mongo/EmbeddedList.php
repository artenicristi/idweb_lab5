<?php

namespace App\Mongo;

use MongoDB\Driver\Cursor;

class EmbeddedList implements \Iterator, \ArrayAccess, \JsonSerializable
{
    /**
     * @var mixed
     */
    protected $dbRefs;

    /**
     * @var array
     */
    protected $items;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string|Document
     */
    private $itemType;

    /**
     * @param $dbRefs
     */
    public function __construct($dbRefs, string $itemType = null)
    {
        $this->position = 0;
        $this->dbRefs = $dbRefs;
        $this->itemType = $itemType;
    }

    public function rewind()
    {
        $this->position = 0;

        $this->loadItems();
    }

    public function current()
    {
        return $this->items[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    /**
     * Load the references from the DB and transforms items into documents
     *
     * @return void
     */
    private function loadItems()
    {
        if (isset($this->items)) {
            return;
        }
        $refs = $this->dbRefs;
        if ($refs instanceof Cursor) {
            $refs = $refs->toArray();
        } else {
            $refs = (array) $refs;
        }
        $firstRef = reset($refs);
        $isRef = !empty($firstRef['$id']);

        if ($isRef) {
            $ids = array_reduce($refs, function ($ids, $ref) {
                $ids[] = $ref['$id'];

                return $ids;
            }, []);

            $items = $this->itemType::find(['_id' => ['$in' => $ids]])->getRaw();
        } else {
            $items = $refs;
        }

        foreach ($items as $item) {
            $this->items[] = $this->itemType::hydrate($item);
        }
    }

    public function offsetExists($offset)
    {
        $this->loadItems();

        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        $this->loadItems();

        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    public function jsonSerialize()
    {
        $this->loadItems();

        return $this->items;
    }
}
