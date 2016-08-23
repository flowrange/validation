<?php

namespace Flowrange\Validation\Tests\Filter;

use Flowrange\Validation\Filter\FilterTestCase;
use function Flowrange\Validation\Filter\defaultValue;

/**
 * DefaultValueFilter tests
 *
 * @author Florent
 */
class DefaultValueFilterTest extends FilterTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder()

            ->test('non-empty value returns value')
                ->value('string')
                ->withFilter(defaultValue('some-value'))
                ->returns('string')

            ->test('0 returns 0')
                ->withFilter(defaultValue('string'))
                ->value(0)
                ->returns(0)

            ->test('empty string returns default value')
                ->withFilter(defaultValue('string'))
                ->value('')
                ->returns('string')

            ->test('null returns default value')
                ->withFilter(defaultValue('string'))
                ->value(null)
                ->returns('string');
    }

}
