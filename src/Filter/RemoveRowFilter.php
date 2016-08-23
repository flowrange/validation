<?php

namespace Flowrange\Validation\Filter;

/**
 * Removes a specific row
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RemoveRowFilter extends \Flowrange\Validation\Filter
{


    /**
     * Key to remove
     * @var string
     */
    private $key;


    /**
     * Constructor
     *
     * @param string $key Key to remove
     */
    public function __construct($key)
    {
        $this->key = $key;
    }


    /**
     * Filter::filter
     */
    public function filter($value, \Flowrange\Validation\Validator $validator)
    {
        unset($value[$this->key]);

        return $value;
    }

}
