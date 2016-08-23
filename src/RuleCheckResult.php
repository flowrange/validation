<?php

namespace Flowrange\Validation;

/**
 * A result for a rule's check method
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RuleCheckResult
{

    /**
     * Whether the result is valid or not
     * @var bool
     */
    private $isValid;


    /**
     * Value that has been checked
     * @var mixed
     */
    private $value;


    /**
     * Errors if any
     * @var string
     */
    private $error;


    /**
     * If the validation result allows to forward to the next rule/filter
     * @var bool
     */
    private $forwardNext;


    /**
     * Return whether the value is valid or not
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }


    /**
     * Return the value that has been checked
     *
     * The value may have been changed by the rule
     *
     * @return array
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * Return the error
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Return whether or not to forward to the next rule/filter
     * @return bool
     */
    public function forwardNext()
    {
        return $this->forwardNext;
    }


    /**
     * Constructor
     *
     * @param bool   $isValid
     * @param mixed  $value
     * @param string $error
     * @param bool   $forwardNext
     */
    public function __construct($isValid, $value, $error = '', $forwardNext = true)
    {
        $this->isValid     = $isValid;
        $this->value       = $value;
        $this->error       = $error;
        $this->forwardNext = $forwardNext;
    }


    /**
     * Returns an new instance
     *
     * * If $isValid is true, the error is forced to be empy
     * * If forwardNext is null, it is set to $isValid (that means that valid rules forwards by default, and invalid
     *   rules stops)
     *
     * @param bool   $isValid
     * @param mixed  $value
     * @param string $error
     * @param bool   $forwardNext
     *
     * @return self
     */
    public static function make($isValid, $value, $error = '', $forwardNext = null)
    {
        if ($isValid) {
            $error = '';
        }
        if ($forwardNext === null) {
            $forwardNext = $isValid;
        }

        return new self($isValid, $value, $error, $forwardNext);
    }

}
