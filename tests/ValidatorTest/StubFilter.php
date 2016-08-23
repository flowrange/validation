<?php

namespace Flowrange\Validation\Tests\ValidatorTest;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Filter;


/**
 * Stub filter for MFW_Validation_Test
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW
 * @subpackage test
 * @since 1.4
 * @version $Id: StubFilter.php 2235 2010-11-02 08:51:31Z florent $
 */
class StubFilter extends Filter
{


    /**
     * MFW_Validation_Filter::filter
     */
    public function filter($value, Validator $validator)
    {
        return 'some-value';
    }
}
