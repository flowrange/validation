<?php

namespace Flowrange\Validation\Filter;


/**
 *
 * @return \Flowrange\Validation\Filter\DefaultValueFilter
 */
function defaultValue($defaultValue)
{
    return new DefaultValueFilter($defaultValue);
}


/**
 *
 * @param $key
 * @return \Flowrange\Validation\Filter\RemoveRowFilter
 */
function removeRow($key)
{
    return new RemoveRowFilter($key);
}

/**
 *
 * @return \Flowrange\Validation\Filter\SanitizeFilter
 */
function sanitize()
{
    return new SanitizeFilter();
}