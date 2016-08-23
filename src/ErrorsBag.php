<?php

namespace Flowrange\Validation;

/**
 * Holds errors
 *
 * Errors are organized in 2 types : global errors and fields errors.
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class ErrorsBag
{


    /**
     * Global errors
     * @var array
     */
    private $globalErrors;


    /**
     * Specific fields errors
     * @var array
     */
    private $fieldsErrors;


    /**
     * Returns whether there's errors
     * @return boolean
     */
    public function hasErrors()
    {
        return count($this->globalErrors) > 0 || count($this->fieldsErrors) > 0;
    }


    /**
     * Returns whether there's an error for the field
     *
     * Only errors in fields error are checked, not global ones
     *
     * @param string $field Field
     *
     * @return bool
     */
    public function hasFieldErrors($field)
    {
        return isset($this->fieldsErrors[$field]) && count($this->fieldsErrors[$field]) > 0;
    }


    /**
     * Returns all global errors
     * @return array
     */
    public function getGlobalErrors()
    {
        return $this->globalErrors;
    }


    /**
     * Returns all fields errors
     * @return array
     */
    public function getFieldsErrors()
    {
        return $this->fieldsErrors;
    }


    /**
     * Return the errors for a specific field
     *
     * @param string $field Field
     *
     * @return string[]
     */
    public function getFieldErrors($field)
    {
        if (isset($this->fieldsErrors[$field])) {
            return $this->fieldsErrors[$field];
        }
        return [];
    }


    /**
     * Constructor
     */
    public function __construct(array $globalErrors = [], array $fieldsErrors = [])
    {
        $this->globalErrors = $globalErrors;
        $this->fieldsErrors = $fieldsErrors;
    }


    /**
     * Add a global error
     *
     * @param string $field Field
     * @param string $error Error
     */
    public function addGlobalError($field, $error)
    {
        if (isset($this->globalErrors[$field]) && in_array($error, $this->globalErrors[$field])) {
            return;
        }

        $this->globalErrors[$field][] = $error;
    }


    /**
     * Add an error for a specific field
     *
     * @param string $field Field
     * @param string $error Error
     */
    public function addFieldError($field, $error)
    {
        $this->fieldsErrors[$field][] = $error;
    }

}
