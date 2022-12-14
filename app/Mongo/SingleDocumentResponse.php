<?php

namespace App\Mongo;

class SingleDocumentResponse
{
    /**
     * @var mixed
     */
    protected $result;

    /**
     * @var string
     */
    protected $itemClass;

    /**
     * @param mixed $result
     */
    public function __construct($result, $itemClass = null)
    {
        $this->result = $result;
        $this->itemClass = $itemClass;
    }

    /**
     * @return mixed|Document|null
     */
    public function get()
    {
        if (empty($this->itemClass)) {
            return $this->result;
        }

        if (empty($this->result)) {
            return null;
        }

        return $this->itemClass::hydrate($this->result);
    }

    public function getRaw()
    {
        $this->itemClass = null;

        return $this->get();
    }

    public function getAsArray()
    {
        $result = $this->getRaw();

       if (method_exists($result, 'toArray')) {
           return $result->toArray();
       }

        return (array) $result;
    }
}
