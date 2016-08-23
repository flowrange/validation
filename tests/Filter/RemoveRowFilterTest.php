<?php

namespace Flowrange\Validation\Tests\Filter;

use Flowrange\Validation\Filter\FilterTestCase;
use function \Flowrange\Validation\Filter\removeRow;

/**
 * RemoveRowFilter tests
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RemoveRowFilterTest extends FilterTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder()

            ->test('does nothing if key does\'nt exists')
                ->value([
                    'key1' => 'value1',
                    'key2' => 'value2'
                ])
                ->withFilter(removeRow('not-exists'))
                ->returns([
                    'key1' => 'value1',
                    'key2' => 'value2'
                ])

            ->test('removes row')
                ->value([
                    'key1' => 'value1',
                    'key2' => 'value2'
                ])
                ->withFilter(removeRow('key2'))
                ->returns([
                    'key1' => 'value1'
                ]);
    }

}
