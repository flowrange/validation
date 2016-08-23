<?php

namespace Flowrange\Validation\Tests\Filter;

/**
 * MFW_Validation_Filter test suite
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage Filter_test
 * @since 1.4
 * @version $Id: AllTests.php 2228 2010-10-25 10:04:43Z florent $
 */
class AllTests extends \PHPUnit_Framework_TestSuite
{


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->addTestFile(__DIR__ . '/DefaultValueFilterTest.php');
        $this->addTestFile(__DIR__ . '/SanitizeFilterTest.php');
    }


    /**
     * Returns the suite
     * @return MFW_Validation_Filter_AllTests
     */
    public static function suite()
    {
        return new AllTests();
    }


}