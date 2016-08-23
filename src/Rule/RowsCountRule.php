<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Array validation
 *
 * * Empty values (null and '') are converted to an empty array
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RowsCountRule extends Rule
{


    /**
     * Error : not an array
     * @var string
     */
    const ERR_NOT_AN_ARRAY = 'rows.not-an-array';


    /**
     * Error : not enough rows
     * @var string
     */
    const ERR_NOT_ENOUGH_ROWS = 'rows.not-enough-rows';


    /**
     * Error : too much rows
     * @var string
     */
    const ERR_TOO_MUCH_ROWS = 'rows.too-much-rows';


    /**
     * Min rows count
     * @var int
     */
    private $min;


    /**
     * Max rows count
     * @var int
     */
    private $max;


    /**
     * Constructor
     *
     * If only one number passed, set it as max rows count. If 2 numbers, set them as min and max rows count
     *
     * @param int $minOrMax Max number of rows if one number provided. Min number if 2 numbers provided
     * @param int $max      Max number of rows if first param set
     */
    public function __construct($minOrMax = null, $max = null)
    {
        if (is_int($minOrMax)) {

            if (is_int($max)) {

                $this->min = $minOrMax;
                $this->max = $max;

            } else {

                $this->max = $minOrMax;
            }
        }
    }


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $array = $value;
        if ($value === '' || $value === null) {
            $array = [];
        }

        if (is_array($array)) {

            if ($this->min > 0 && count($value) < $this->min) {

                return RuleCheckResult::make(false, $value, self::ERR_NOT_ENOUGH_ROWS);
            }

            if ($this->max > 0 && count($value) > $this->max) {

                return RuleCheckResult::make(false, $value, self::ERR_TOO_MUCH_ROWS);
            }

            return RuleCheckResult::make(true, $array);
        }

        return RuleCheckResult::make(false, $value, self::ERR_NOT_AN_ARRAY);
    }
}
