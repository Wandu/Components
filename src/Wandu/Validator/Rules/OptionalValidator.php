<?php
namespace Wandu\Validator\Rules;

class OptionalValidator extends ValidatorAbstract
{
    const ERROR_TYPE = 'type.integer';
    const ERROR_MESSAGE = 'it must be the integer';

    public function validate($item)
    {
        return is_int($item);
    }
}
