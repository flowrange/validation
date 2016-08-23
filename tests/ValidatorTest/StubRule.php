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
class StubRule extends Rule
{


    /**
     * MFW_Validation_Rule::isValid
     */
    public function isValid($value, Validator $validator)
    {
        $this->lastValue = 'some-value';
        return true;
    }
}
