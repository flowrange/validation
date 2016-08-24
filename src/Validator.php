<?php

namespace Flowrange\Validation;

use Flowrange\Validation\RuleCollection;
use Flowrange\Validation\Rule\OptionalRule;

/**
 * Data validation & sanitization using rules & filters
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 * @copyright Copyright © 2015 Florent Geffroy
 * @license See license.md
 * @package flowrange/validation
 * @version $Id$
 */
class Validator
{


    /**
     * Rules/filters
     *
     * Nested array of field name => RuleCollection | sub-fields
     *
     * @see addRules
     * @see RuleCollection
     * @var array
     */
    private $rules;


    /**
     * Last data checked
     * @var array
     */
    private $data;


    /**
     * Last errors
     * @var ErrorsBag
     */
    private $errors;


    /**
     * Post check listeners
     * @var array
     */
    private $postCheckListeners = [];


    /**
     * Returns the last data checked
     * @var array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Returns the last errors
     * @var ErrorsBag
     */
    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rules = [];
    }


    /**
     * Add rules/filters
     *
     * Pass an array field => rules. Arrays may be nested, and field may be rows. Quick example :
     * <code>
     * $validator->addRules([
     *     'name'     => rules(text(),  sanitize(), required(), length(100))
     *     'emails[]' => rules(email(), required(), length(255))
     *     'phones[]' => [
     *          'label'  => rules(text(), sanitize(), optional(), length(100)),
     *          'number' => rules(text(), sanitize(), required(), length(20))
     *     ]
     * ])
     * </code>
     *
     * @param array $defs
     *
     * @return self
     */
    public function addRules(array $defs)
    {
        $this->rules = array_merge_recursive($this->rules, $defs);
        return $this;
    }


    /**
     * Check some data
     *
     * @param array $data Data to check (usually $_POST)
     *
     * @return bool Whether the data are valid or not
     */
    public function check($data)
    {
        $this->data   = $data;
        $this->errors = new ErrorsBag();

        $valid = $this->checkArrayRecursive($this->rules, $this->data, '');

        if ($valid && count($this->postCheckListeners) > 0) {
            foreach ($this->postCheckListeners as $callback) {
                $callback($this->data);
            }
        }

        return $valid;
    }


    /**
     * Check an array recursively
     *
     * @param array $rules        Rules for this array
     * @param array $data         Data to check
     * @param type  $parent
     * @param type  $isParentRows
     *
     * @return bool
     */
    private function checkArrayRecursive($rules, array &$data, $parent = '', $isParentRows = false)
    {
        $valid = null;

        $fieldIndex = 0;

        foreach ($rules as $field => $rulesOrSubFields) {

            $rulesInstanceOfRuleCollection = ($rulesOrSubFields instanceof RuleCollection);
            $rulesAreSubFields             = is_array($rulesOrSubFields);

            $valueIfMissing = ($rulesInstanceOfRuleCollection ? '' : []);

            // Do not use list() operator, we need a reference to the values or else we won't be able to modify $data
            $fieldNameAndValues = self::getFieldNameAndValues($field, $data, $valueIfMissing, $fieldIndex);
            $fieldName          =  $fieldNameAndValues[0];
            $values             = &$fieldNameAndValues[1];

            $fieldErrorName = $parent ? $parent . '[' . $fieldName . ']' : $fieldName;

            if ($rulesInstanceOfRuleCollection) {

                $tempIsParentRows = (substr($field, -2) === '[]');

                $valueIndex     = 0;

                foreach ($values as &$value) {

                    $valueErrorName = $fieldErrorName;
                    if ($tempIsParentRows) {
                        $valueErrorName .= '[' . $valueIndex++ . ']';
                    }

                    foreach ($rulesOrSubFields->getRules() as $object) {

                        if($object instanceof Rule) {

                            $result = $object->check($value, $this);

                            $value = $result->getValue();

                            if($result->isValid()) {

                                $valid = ($valid === null ? true : $valid);

                            } else {

                                $valid = false;
                                $error = $result->getError();

                                if (preg_match('/\[\d+\]/', $valueErrorName)) {

                                    $genericValueName = preg_replace('/\[\d+\]/', '[]', $valueErrorName);
                                    $this->errors->addGlobalError($genericValueName, $error);

                                } else {
                                    $this->errors->addGlobalError($valueErrorName, $error);
                                }

                                $this->errors->addFieldError($valueErrorName, $error);
                            }

                            if (!$result->forwardNext()) {
                                break;
                            }

                        } elseif($object instanceof Filter) {

                            $value = $object->filter($value, $this);
                        }
                    }
                }
                unset($value);

            } else if($rulesAreSubFields) {

                $isCurrentFieldRows = (substr($field, -2) === '[]');
                $subValueIndex = 0;

                if (!is_array($values)) {
                    $values = [];
                }

                foreach ($values as &$value) {

                    $subFieldName = $fieldErrorName;
                    if ($isCurrentFieldRows) {
                        $subFieldName .= '[' . $subValueIndex++ . ']';
                    }
                    $result = $this->checkArrayRecursive($rulesOrSubFields, $value, $subFieldName, $isCurrentFieldRows);
                    $valid = ($result === true ? ($valid === null ? true : $valid) : false);
                }
                unset($value);
            }

            $fieldIndex++;
        }

        return ($valid === null ? false : $valid);
    }


    /**
     * Parse a field identifier, and return its field name and values
     *
     * As a field name may contain [] to tell to look for rows, this function returns the field name without [].
     *
     * As the checkArrayRecursive method operates on an array of values, a single value is put into an array if needed.
     *
     * The $default param is not a default value if empty (there's a filter for that), but a default value if the field
     * doesn't exists : use either empty string or empty array
     *
     * If a field doesn't exists in the data, the $index param allows us to insert an empty value at the right position.
     * This is useful for debugging, missing fields will appear at the expected position, not at the end
     *
     * @param string $field   Field identifier (may contain brackets)
     * @param array  &$data   Data
     * @param mixed  $default Default values if none exists
     * @param int    $index   Field index
     *
     * @return [string, array] Field name and field data
     */
    private static function getFieldNameAndValues($field, array &$data, $default, $index)
    {
        $areFieldValuesRows = (substr($field, -2) === '[]');

        if ($areFieldValuesRows) {
            $fieldName = substr($field, 0, strlen($field) - 2);
            $default   = [];
        } else {
            $fieldName = $field;
        }

        if (!array_key_exists($fieldName, $data)) {

            // Only way to insert something in an string-indexed array at a specific position
            $data = array_slice($data, 0, $index, true) +
                    [$fieldName => $default] +
                    array_slice($data, $index, null, true);
        }

        if ($areFieldValuesRows) {
            $values = &$data[$fieldName];
        } else {
            $values = [&$data[$fieldName]];
        }

        return [$fieldName, &$values];
    }


    /**
     * Adds a callback to execute after check() is completed and data are valid
     *
     * @param type $callback
     *
     * @return self
     */
    public function postCheck($callback)
    {
        $this->postCheckListeners[] = $callback;
        return $this;
    }


    /**
     * Creates a new Validator
     *
     * Use for fluent interface
     *
     * @return Validator
     */
    public static function create()
    {
        return new self();
    }

}
