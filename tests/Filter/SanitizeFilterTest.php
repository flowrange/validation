<?php

namespace Flowrange\Validation\Tests\Filter;


use Flowrange\Validation\Filter\FilterTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Filter as f;


/**
 * SanitizeFilter tests
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class SanitizeFilterTest extends FilterTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder(f\sanitize())

            ->test('string value left unchanged')
                ->value('string')

            ->test('numeric value left unchanged')
                ->value(123)

            ->test('left spaces trimmed')
                ->value('  string')
                ->returns('string')

            ->test('right spaces trimmed')
                ->value('string  ')
                ->returns('string')

            ->test('new lines removed')
                ->value("str\r\ning")
                ->returns('string')

            ->test('inner new lines kept and normalized if keepLines is true')
                ->value("s\ro\nme str\r\ni\rng")
                ->withFilter(
                    f\sanitize()->keepLines(true))
                ->returns("s\no\nme str\ni\nng")

            ->test('outer new lines trimmed but inner ones kept if keepLines is true')
                ->value("\r\n\rsome \nstr\ning\n")
                ->withFilter(
                    f\sanitize()->keepLines(true))
                ->returns("some \nstr\ning")

            ->test('special chars trimmed and removed')
                ->value("\xC2\xA0 \t\r \x03 some s\xC2\xA0t\x04ri\x00ng \x12 \n\xC2\xA0\t\t ")
                ->returns("some s\xC2\xA0tring");
    }

}
