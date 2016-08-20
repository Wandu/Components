<?php
namespace Wandu\Validator\Rules;

use Wandu\Validator\Contracts\ValidatorInterface;
use Wandu\Validator\Exception\InvalidValueException;
use function Wandu\Validator\validator;

/**
 * @method \Wandu\Validator\Rules\PipelineValidator optional(\Wandu\Validator\Contracts\ValidatorInterface $validator = null)
 * @method \Wandu\Validator\Rules\PipelineValidator not(\Wandu\Validator\Contracts\ValidatorInterface $validator)
 * @method \Wandu\Validator\Rules\PipelineValidator array(array $attributes = [])
 * @method \Wandu\Validator\Rules\PipelineValidator object(array $properties = [])
 * @method \Wandu\Validator\Rules\PipelineValidator integer()
 * @method \Wandu\Validator\Rules\PipelineValidator float()
 * @method \Wandu\Validator\Rules\PipelineValidator string()
 * @method \Wandu\Validator\Rules\PipelineValidator min(int $min)
 * @method \Wandu\Validator\Rules\PipelineValidator max(int $max)
 * @method \Wandu\Validator\Rules\PipelineValidator lengthMin(int $min)
 * @method \Wandu\Validator\Rules\PipelineValidator lengthMax(int $max)
 * @method \Wandu\Validator\Rules\PipelineValidator email(\Egulias\EmailValidator\Validation\EmailValidation $validation = null)
 */
class PipelineValidator implements ValidatorInterface
{
    /** @var array|\Wandu\Validator\Contracts\ValidatorInterface[] */
    protected $validators;
    
    /**
     * @param \Wandu\Validator\Contracts\ValidatorInterface[] $validators
     */
    public function __construct(array $validators = [])
    {
        $this->validators = $validators;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return \Wandu\Validator\Rules\PipelineValidator
     */
    public function __call($name, array $arguments = [])
    {
        $this->push(validator()->__call($name, $arguments));
        return $this;
    }

    /**
     * @param \Wandu\Validator\Contracts\ValidatorInterface $validator
     * @return \Wandu\Validator\Rules\PipelineValidator
     */
    public function push(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function assert($item)
    {
        /** @var \Wandu\Validator\Exception\InvalidValueException[] $exceptions */
        $exceptions = [];
        foreach ($this->validators as $validator) {
            try {
                $validator->assert($item);
            } catch (InvalidValueException $exception) {
                $exceptions[] = $exception;
            }
        }
        if (count($exceptions)) {
            $baseException = $exceptions[0];
            for ($i = 1, $length = count($exceptions); $i < $length; $i++) {
                $baseException->appendTypes($exceptions[$i]->getTypes());
            }
            throw $baseException;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate($item)
    {
        foreach ($this->validators as $validator) {
            if (!$validator->validate($item)) {
                return false;
            }
        }
        return true;
    }
}
