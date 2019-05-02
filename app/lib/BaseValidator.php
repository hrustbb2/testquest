<?php

namespace app\lib;

abstract class BaseValidator
{
    /**
     * @var Array
     */
    private $errors = [];

    /**
     * @return Array
     */
    abstract public function getRules();

    /**
     * @return Array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param $field string
     * @param $errorMessage string
     * @return void
     */
    public function addError($field, $errorMessage)
    {
        $this->errors[$field][] = $errorMessage;
    }

    /**
     * @param $attributes Array
     * @return boolean
     */
    public function validate($attributes)
    {
        $result = true;
        $rules = $this->getRules();
        foreach ($attributes as $key=>$val) {
            $validators = $rules[$key] ?? [];
            foreach ($validators as $validator) {
                if (!$validator['validator']->validate($val)) {
                    $this->errors[$key][] = $validator['message'];
                    if ($result) {
                        $result = false;
                    }
                }
            }
        }
        return $result;
    }
}
