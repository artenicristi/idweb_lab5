<?php

namespace App\Mongo;

use MongoDB\Driver\Cursor;

class MultipleDocumentsResponse extends SingleDocumentResponse
{
    /**
     * @return mixed
     */
    public function get()
    {
        if($this->result instanceof Cursor) {
            $result = $this->result->toArray();
        } else {
            $result = $this->result;
        }

        if (empty($this->itemClass)) {
            return $result;
        }

        foreach ($result as $key => $item) {
            $result[$key] = $this->itemClass::hydrate((array) $item);
        }

        return $result;
    }
}
