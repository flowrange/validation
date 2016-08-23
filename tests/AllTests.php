<?php

namespace Flowrange\Validation\Tests;

/**
 * MFW_Validator test suite
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validator
 * @subpackage test
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

        $this->addTestFile(__DIR__ . '/ValidatorTest.php');
        $this->addTestFile(__DIR__ . '/Filter/AllTests.php');
        $this->addTestFile(__DIR__ . '/Rule/AllTests.php');
    }


    /**
     * Returns the suite
     * @return MFW_Validator_Rule_AllTests
     */
    public static function suite()
    {
        return new AllTests();
    }


}