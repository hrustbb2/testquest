<?php

namespace app\lib;

class BaseEntity
{
    /**
     * @param $data Array
     * @return void
     */
    public function load($data)
    {
        foreach ($data as $key=>$item) {
            if (property_exists($this, $key) || method_exists($this, 'set'.ucfirst($key))) {
                $this->$key = $item;
            }
        }
    }

    /**
     * @param $attr Array
     * @return Array
     */
    public function getAttributes($attr)
    {
        $result =[];
        foreach ($attr as $item) {
            $result[$item] = $this->{$item};
        }
        return $result;
    }
}
