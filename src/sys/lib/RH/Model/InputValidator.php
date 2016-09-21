<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * Utilities for validating the values from \RH\Model\Input to put into another model.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class InputValidator
{

    /** @var int Flag to denote that a value cannot be empty. */
    const NON_EMPTY = 1;

    /** @var int Flag to denote that a value must be numeric. */
    const T_NUMERIC = 2;

    /** @var int Flag to denote that a value must be an integer (in int or string format). */
    const T_INT = 4;

    /** @var int Flag to denote that a value must be a boolean type. */
    const T_BOOL = 8;

    /** @var int Flag to denote that a value must be an email address. */
    const T_STR_EMAIL = 16;

    /**
     * @var int
     *  Flag to denote that a value must be bool in string format. If you use this flag, the value *will* be converted
     *  to either 1, 0, or -1.
     */
    const T_BOOL_STR = 32;

    /** @var \RH\Model\Input to validate and move to intended model. */
    private $mInput;

    /** @var \RH\Model\AbstractModel Model that is to be populated. */
    private $mModel;

    /**
     * Construct the validator for a particular model.
     *
     * @param \RH\Model\Input $input Input to validate and move to intended model if it's valid.
     * @param \RH\Model\AbstractModel $model Model to be populated.
     */
    public function __construct(\RH\Model\Input $input, \RH\Model\AbstractModel $model)
    {
        $this->mInput = $input;
        $this->mModel = $model;
    }

    /**
     * Test whether a value within the model is seet and meets the dertermiend type.
     *
     * @param string $lang Language string to return in any error message
     * @param string $inputKey Key from the input to validate
     * @param bool $require `true` if the value is required
     * @param int $type Bitwise type (see class constants)
     * @param function $fn
     *  Optional validation function that should return `true` or `false` and takes the value as its only parameter.
     * @param string $identKey Key that will identify the problematic row to the user - this should match the input key.
     * @return bool `true`
     * @throws \RH\Error\InvalidInput if the value does not pass
     */
    public function test($lang, $inputKey, $require = true, $type = 0, $fn = null, $identKey = null)
    {
        if (!\is_null($identKey) && isset($this->mInput->$identKey)) {
            $identStr = ' for "' . $this->mInput->$identKey .'"';
        } else {
            $identStr = '';
        }

        if (!isset($this->mInput->$inputKey) && $require) {
            throw new \RH\Error\InvalidInput($lang . " is required and was not supplied");
        }

        $value = $this->mInput->$inputKey;
        return $this->testValue($lang, $value, $type, $fn, $identStr);
    }

    /**
     * Test whether many keys within the model are set and meet the dertermiend type.
     *
     * If all values are *all* blank `false` is retured, `true` if they all pass, and an exception otherwise.
     *
     * Additionally, a key that will allow the user to identify the row can be set.
     *
     * @param mixed[] $data Data to test, matches the paramters of {@see #test()} in an numerical array.
     * @param string $identKey Key that will identify the problematic row to the user - this should match the input key.
     * @return bool `true` if pass, `false` if all blank.
     * @throws \RH\Error\InvalidInput if the value does not pass.
     */
    public function testAll(array $data, $identKey = null)
    {
        if (!\is_null($identKey) && isset($this->mInput->$identKey)) {
            $label = '';
            foreach ($data as $datum) {
                if ($datum[1] === $identKey) {
                    $label = $datum[0];
                    break;
                }
            }
            
            $identStr = ' for '. $label .' "' . $this->mInput->$identKey .'"';
        } else {
            $identStr = '';
        }

        $notEmpty = false;
        foreach ($data as $datum) {
            if (!isset($this->mInput->$datum[1]) && $datum[3]) {
                throw new \RH\Error\InvalidInput($datum[0] . " is required and was not supplied.");
            }

            if (!empty($this->mInput->$datum[1])) {
                $notEmpty = true;
            }
        }

        if (!$notEmpty) {
            return false;
        }

        $emptyVals = 0;
        foreach ($data as $datum) {
            $this->testValue($datum[0], $this->mInput->$datum[1], $datum[4], $datum[5], $identStr);
        }

        return true;
    }

    /**
     * Test whether a value within the model is set and meets the determined type, and if it does, set it in the model.
     *
     * @param string $lang Language string to return in any error message.
     * @param string $inputKey Key from the input to validate.
     * @param string $modelKey Final key for the populated model.
     * @param bool $require `true` if the value is required.
     * @param int $type Bitwise type (see class constants).
     * @param boolean $reformat
     *  If true, can reformat the value to the expected type – warning: with multiple types in the bitmast for type,
     *  this may have unintended consequences.
     * @param function $fn
     *  Optional validation function that should return `true` or `false` and takes the value as its only parameter.
     * @param string $identKey Key that will identify the problematic row to the user - this should match the input key.
     * @return bool `true` if the value was set in the model, `false` otherwise.
     * @throws \RH\Error\InvalidInput if the value does not pass.
     */
    public function testAndSet(
        $lang,
        $inputKey,
        $modelKey,
        $require = true,
        $type = 0,
        $reformat = true,
        $fn = null,
        $identKey = null
    ) {
    
        if ($this->test($lang, $inputKey, $require, $type, $fn, $identKey)) {
            $value = $this->mInput->$inputKey;

            if ($reformat) {
                $value = $this->reformatValue($type, $value);
            }

            $this->mModel->$modelKey = $value;
            return true;
        }
        return $false;
    }

    /**
     * Test whether many value within the model is seet and meets the determined type and set the the values if they
     * all pass.
     *
     * If all values are *all* blank `false` is retured, `true` if they all pass, and an exception otherwise.
     *
     * @param mixed[] $data Data to test, matches the paramters of {@see #testAll()} in an numerical array.
     * @param boolean $reformat
     *  If true, can reformat the value to the expected type – warning: with multiple types in the bitmast for type,
     * @param string $identKey Key that will identify the problematic row to the user - this should match the input key.
     * @return bool `true` if pass, `false` if all blank.
     * @throws \RH\Error\InvalidInput if the value does not pass
     */
    public function testAndSetAll(array $data, $reformat = false, $identKey = null)
    {
        if ($this->testAll($data, $identKey)) {
            foreach ($data as $datum) {
                $value = $this->mInput->$datum[1];

                if ($reformat) {
                    $value = $this->reformatValue($datum[4], $value);
                }

                $this->mModel->$datum[2] = $value;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Test whether a value is set and meets the dertermied type.
     *
     * A value that will allow the user to identify the row can be set, this should be a user-friendly string.
     *
     * @param string $lang Language string to return in any error message
     * @param string $value Value to test.
     * @param int $type Bitwise type (see class constants)
     * @param function $fn
     *  Optional validation function that should return `true` or `false` and takes the value as its only parameter.
     * @param string $identStr String that will identify the problematic row to the user, include a space char before!
     * @return bool `true`
     * @throws \RH\Error\InvalidInput if the value does not pass
     */
    private function testValue($lang, $value, $type = 0, $fn = null, $identStr = "")
    {
        if (($type & self::NON_EMPTY) && empty($value)) {
            throw new \RH\Error\InvalidInput($lang .' cannot be empty' . $identStr .'.');
        }

        if (($type & self::T_NUMERIC) && !\is_numeric($value)) {
            throw new \RH\Error\InvalidInput($lang .' must be numeric' . $identStr .' ("'. $value .'" given).');
        }

        if (($type & self::T_INT) && ((string)(int) $value) !== $value) {
            throw new \RH\Error\InvalidInput($lang .' must be an integer' . $identStr .' ("'. $value .'" given).');
        }

        if (($type & self::T_BOOL) && !\is_bool($value)) {
            throw new \RH\Error\InvalidInput($lang .' must be a boolean' . $identStr .' ("'. $value .'" given).');
        }

        if (($type & self::T_BOOL_STR) && self::str2int($value) === -1) {
            throw new \RH\Error\InvalidInput($lang .' must be either "true" or "false" only'. $identStr .
                ' ("'. $value .'" given).');
        }

        if (($type & self::T_STR_EMAIL) && \filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new \RH\Error\InvalidInput($lang .' must be an email address'. $identStr .
                ' ("'. $value .'" given).');
        }

        if (!\is_null($fn) && !$fn($value)) {
            throw new \RH\Error\InvalidInput($lang .' is invalid' . $identStr .' ("'. $value .'" given).');
        }

        return true;
    }

    /**
     * Reformat a value to the specified type. This is dangerous! Use carefully.
     *
     * @param int $type Bitwise type (see class constants)
     * @param string $value Value to convert.
     */
    private function reformatValue($type, $value)
    {
        if ($type & self::T_NUMERIC) {
            if (strpos($value, '.')) {
                return \floatval($value);
            } else {
                return \intval($value);
            }
        } elseif ($type & self::T_INT) {
            return \intval($value);
        } elseif ($type & self::T_BOOL) {
            return \boolval($value);
        } elseif ($type & self::T_BOOL_STR) {
            return self::str2int($value);
        }

        return $value;
    }

    /**
     * Convert "true" to 1 and "false" to 0, everything else to -1.
     *
     * @param string $value String to convert
     * @return mixed Boolean value or -1
     */
    private static function str2int($value)
    {
        return $value == 'true' ? true : ($value == 'false' ? false : -1);
    }
}
