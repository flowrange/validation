<?php

namespace Flowrange\Validation\Filter;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Filter;


/**
 * String sanitization
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class SanitizeFilter extends Filter
{


    private $keepLines = false;


    public function keepLines($keepLines = true)
    {
        $this->keepLines = $keepLines;
        return $this;
    }


    /**
     * Filter::filter
     */
    public function filter($value, Validator $validator)
    {
        if(is_string($value)) {

            return
                preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u',  '',
                preg_replace('/[\x00-\x08\x0b-\x1f]/',   '',
                str_replace(["\r\n", "\r"], ($this->keepLines ? "\n" : ''),
                $value)));
        }
        return $value;
    }


}