<?php

namespace Flowrange\Validation\Rule\RuleTestCase;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;

/**
 * Data builder for MFW_Validation_RuleTestCase::testRule
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage Rule_test
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
     * Default Rule
     * @var Rule
     */
    private $defaultRule;


    /**
     * Current value
     * @var int
     */
    private $currentValue;


    /**
     * Whether current values are valid or not
     * @var bool
     */
    private $currentValid;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = array();
    }


    /**
     * Default rule
     */
    public function defaultRule(Rule $rule)
    {
        $this->defaultRule = $rule;
        return $this;
    }


    /**
     * Expects the following values to be valid
     *
     * By default valid rules forwards to the next rule
     *
     * @return self
     */
    public function areValid()
    {
        $this->currentValid = true;
        return $this;
    }


    /**
     * Expects the following values to be not valid
     *
     * By default, not valid rules do not forward to the next rule
     *
     * @return self
     */
    public function areNotValid()
    {
        $this->currentValid = false;
        return $this;
    }


    /**
     * Defines a test name
     *
     * @param mixed $name
     *
     * @return self
     */
    public function test($name)
    {
        $this->currentValue = count($this->data);
        $this->data[$this->currentValue] = [
            'name'        => $name,
            'valid'       => $this->currentValid,
            'forwardNext' => $this->currentValid
        ];
        return $this;
    }


    /**
     * Sets the checked value
     *
     * @param mixed $value
     *
     * @return self
     */
    public function value($value)
    {
        $this->data[$this->currentValue]['value'] = $value;
        return $this;
    }


    /**
     * Sets a different rule for this value
     *
     * @param Rule $r
     *
     * @return self
     */
    public function withRule(Rule $r)
    {
        $this->data[$this->currentValue]['rule'] = $r;
        return $this;
    }


    /**
     * Sets a validator instance to pass to the check method
     *
     * @return self
     */
    public function withValidator(Validator $validator)
    {
        $this->data[$this->currentValue]['validator'] = $validator;
        return $this;
    }


    /**
     * Sets an expected result value
     *
     * @param mixed $expected
     *
     * @return self
     */
    public function returns($expected)
    {
        $this->data[$this->currentValue]['expected'] = $expected;
        return $this;
    }


    /**
     * Sets an expected error
     *
     * @param mixed $error
     *
     * @return self
     */
    public function error($error)
    {
        $this->data[$this->currentValue]['error'] = $error;
        return $this;
    }



    /**
     * Tells that the rule should forward to the next rule
     *
     * @return self
     */
    public function forwardsToNext()
    {
        $this->data[$this->currentValue]['forwardNext'] = true;
        return $this;
    }


    /**
     * Tells that the rule should not forward to the next rule
     *
     * @return self
     */
    public function doesntForwardToNext()
    {
        $this->data[$this->currentValue]['forwardNext'] = false;
        return $this;
    }


    /**
     * Builds the data as expected by testRule
     *
     * @return array
     */
    public function build()
    {
        $data = array();
        foreach($this->data as $testData) {

            $row = [];

            if(isset($testData['rule'])) {
                $row[] = $testData['rule'];
            } else {
                $row[] = $this->defaultRule;
            }

            $row[] = $testData['value'];
            $row[] = $testData['valid'];

            if(array_key_exists('expected', $testData)) {
                $row[] = $testData['expected'];
            } else {
                $row[] = $testData['value'];
            }

            if (isset($testData['error'])) {
                $row[] = $testData['error'];
            } else {
                $row[] = '';
            }

            $row[] = $testData['forwardNext'];

            if (isset($testData['validator'])) {
                $row[] = $testData['validator'];
            } else {
                $row[] = new Validator();
            }

            $data[$testData['name']] = $row;
        }
        return $data;
    }


}