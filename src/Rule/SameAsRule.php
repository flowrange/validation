<?php

namespace Flowrange\Validation\Rule;

/**
 * Description of SameAsRule
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class SameAsRule extends \Flowrange\Validation\Rule
{


    /**
     * Error : value is not equal to other field
     */
    const ERR_NOT_EQUAL = 'same-as.not-equal';


    /**
     * Field checked against
     * @var string
     */
    private $field;


    /**
     * Sets the field checked agains
     * @param string $field Field name
     */
    public function field($field)
    {
        $this->field = $field;
        return $this;
    }


    public function isValid($value, \Flowrange\Validation\Validator $validator)
    {
        if ($value === $validator->getFieldValue($this->field)) {
            return true;
        }
        $this->lastError = self::ERR_NOT_EQUAL;
        return false;
    }

}
