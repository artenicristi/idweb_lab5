<?php

namespace App\Mongo;

class EmbeddedObject
{
    /**
     * @var mixed
     */
    private $__instance;

    /**
     * @var mixed
     */
    private $__data;

    /**
     * @var string
     */
    private $__itemType;

    /**
     * @param array $data
     * @param string $itemType
     */
    public function __construct($data, string $itemType)
    {
        $this->__data = $data;

        if ($itemType) {
            $this->__itemType = $itemType;
        }
    }

    public function __get($name)
    {
        if (!isset($this->__instance)) {
            $this->loadInstance();
        }

        if ($this->__instance instanceof Document)
        {
            return $this->__instance->{$name};
        }

        return $this->__instance[$name];
    }

    public function __call($name, $arguments)
    {
        if (!isset($this->__instance)) {
            $this->loadInstance();
        }

        return call_user_func_array([$this->__instance, $name], $arguments);
    }

    private function loadInstance()
    {
        $isRef = !empty($this->__data['$id']);

        if ($isRef) {
            if ($this->__itemType) {
                $this->__instance = $this->__itemType::findOneById($this->__data['$id'])->get();
            } else {
                $this->__instance = Document::getConnection()->getDb()->selectCollection($this->__data['$ref'])
                    ->findOne(['_id' => $this->__data['$id']]);
            }
        } else {
            $this->__instance = $this->__itemType ? $this->__itemType::hydrate($this->__data) : $this->__data;
        }
    }
}
