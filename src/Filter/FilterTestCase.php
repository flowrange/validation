<?php

namespace Flowrange\Validation\Filter;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Filter;


/**
 * Base testcase for MFW_Validation_Filter implementations
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage Filter_test
 * @since 1.4
 * @version $Id: FilterTestCase.php 2232 2010-10-28 10:27:57Z florent $
 */
abstract class FilterTestCase extends \PHPUnit_Framework_TestCase
{


    /**
     * data provider for testFilter
     */
    public function filterTestProvider()
    {
        $dataBuilder = $this->initDataBuilder();

        if (!$dataBuilder instanceof \Flowrange\Validation\Filter\FilterTestCase\DataBuilder) {
            throw new \Exception('initDataBuilder should return a valid DataBuilder');
        }

        return $this->initDataBuilder()->build();
    }


    /**
     * Inits and returns a data builder
     */
    public abstract function initDataBuilder();


    /**
     * Returns a data builder
     * @param \Flowrange\Validation\Tests\Filter $defaultFilter
     * @return \Flowrange\Validation\Tests\FilterTestCase\DataBuilder
     */
    public function getDataBuilder(Filter $defaultFilter = null)
    {
        $dataBuilder = new FilterTestCase\DataBuilder();

        if ($defaultFilter) {
            $dataBuilder->defaultFilter($defaultFilter);
        }

        return $dataBuilder;
    }


    /**
     * filter test
     * @dataProvider filterTestProvider
     */
    public function testFilter($filter, $value, $expected, $validator)
    {
        $this->assertSame(
            $expected,
            $filter->filter($value, $validator));
    }


}