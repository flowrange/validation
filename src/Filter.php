<?php

namespace Flowrange\Validation;

/**
 * Base class for filters
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 * @copyright Copyright © 2015 Florent Geffroy
 * @license See license.md
 * @package flowrange/validation
 * @version $Id$
 */
abstract class Filter
{


    /**
     * Constructor
     */
    public function __construct()
    {
    }


    /**
     * Filter a value
     * @param mixed $value
     * @return mixed
     */
    public abstract function filter($value, Validator $validator);


}