<?php

namespace app\models\task\validators;

use Respect\Validation\Rules\AbstractRule;

class SortBy extends AbstractRule
{
    /**
     * @inheritdoc
     */
    public function validate($input)
    {
        return in_array($input, [null, 'name', 'email', 'status']);
    }
}
