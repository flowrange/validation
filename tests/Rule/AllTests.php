<?php

namespace Flowrange\Validation\Tests\Rule;

/**
 * Rules test suite
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class AllTests extends \PHPUnit_Framework_TestSuite
{


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        foreach([
            'CallbackRule',
            'DateRule',
            'EmailRule',
            'FileRule',
            'HourRule',
            'ImageRule',
            'InArrayRule',
            'IntegerRule',
            'LengthRule',
            'MoneyRule',
            'NumberRule',
            'OptionalRule',
            'RangeRule',
            'RegexpRule',
            'RequiredRule',
            'RowsCountRule',
            'TextRule',
            'ValueRule'] as $test) {

            $this->addTestFile(__DIR__ . '/' . $test . 'Test.php');
        }
    }


    /**
     * Returns the suite
     * @return AllTests
     */
    public static function suite()
    {
        return new self();
    }

}
