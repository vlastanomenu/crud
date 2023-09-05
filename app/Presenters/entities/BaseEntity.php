<?php

namespace App\Presenters\entities;

abstract class BaseEntity
{
    public function toArray()
    {
        return get_object_vars($this);
    }

    public function fromArray(array $data)
    {
        foreach ($data as $key => $item)
        {
            $this->{$key} = $item;
        }
        return $this;
    }
}