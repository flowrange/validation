<?php

namespace Flowrange\Validation\Filter\FilterTestCase;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Filter;


/**
 * Médiagonale Framework
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage Filter_test
 * @since 1.4
 * @version $Id: DataBuilder.php 2234 2010-10-28 13:32:13Z florent $
 */


/**
 * Data builder for MFW_Validation_FilterTestCase::testFilter
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage Filter_test
 * @since 1.4
 * @version $Id: DataBuilder.php 2234 2010-10-28 13:32:13Z florent $
 */
class DataBuilder
{


    /**
     * Data
     * @var array
     */
    private $data;


    /**
     * Default Filter
     * @var Filter
     */
    private $defaultFilter;


    /**
     * Current value
     * @var int
     */
    private $currentValue;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = array();
    }


    /**
     * Default filter
     */
    public function defaultFilter(Filter $filter)
    {
        $this->defaultFilter = $filter;
        return $this;
    }


    /**
     * Defines a test name
     * @param mixed $name
     */
    public function test($name)
    {
        $this->currentValue = count($this->data);
        $this->data[$this->currentValue] = array('name' => $name);
        return $this;
    }


    /**
     * Adds a value
     * @param mixed $value
     */
    public function value($value)
    {
        $this->data[$this->currentValue]['value'] = $value;
        return $this;
    }


    /**
     * Sets a different filter for this value
     * @param mixed $options
     */
    public function withFilter(Filter $filter)
    {
        $this->data[$this->currentValue]['filter'] = $filter;
        return $this;
    }


    /**
     * Sets a validator instance to pass to isValid
     * @param MFW_Validation_Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $this->data[$this->currentValue]['validator'] = $validator;
        return $this;
    }


    /**
     * Adds an expected result to the current value
     * @param mixed $expected
     */
    public function returns($expected)
    {
        $this->data[$this->currentValue]['expected'] = $expected;
        return $this;
    }


    /**
     * Builds the data as expected by testRule
     * @return array
     */
    public function build()
    {
        $data = array();
        foreach($this->data as $value) {

            $row = array();

            if(isset($value['filter'])) {
                $row[] = $value['filter'];
            } else {
                $row[] = $this->defaultFilter;
            }

            $row[] = $value['value'];

            if(array_key_exists('expected', $value)) {
                $row[] = $value['expected'];
            } else {
                $row[] = $value['value'];
            }

            if (isset($value['validator'])) {
                $row[] = $value['validator'];
            } else {
                $row[] = new Validator();
            }

            $data[$value['name']] = $row;
        }
        return $data;
    }


}