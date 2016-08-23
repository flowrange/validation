<?php

namespace Flowrange\Validation\Tests\ValidatorTest;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;

/**
 * Stub rule for MFW_Validation_Test
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage test
 * @since 1.4
 * @version $Id: StubRule.php 2235 2010-11-02 08:51:31Z florent $
 */
class CheckDataRule extends Rule
{


    private $expectedData;


    /**
     * Constructor
     */
    public function __construct($expectedData)
    {
        $this->expectedData = $expectedData;
    }


    /**
     * MFW_Validation_Rule::isValid
     */
    public function isValid($value, Validator $validator)
    {
        \PHPUnit_Framework_Assert::assertSame(
            $this->expectedData,
            $validator->getData());

        return true;
    }


}