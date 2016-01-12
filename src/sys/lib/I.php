<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

/**
 * Singleton manager for the Research Highlights submission system.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
final class I
{

    /** @var RH Instance of the `RH` class */
    private static $instance = null;
    
    /** @var Object[] All Singleton instances */
    private static $objects = array ();
    
    /**
     * Disable public construction of the `RH` class.
     */
    private function __construct()
    {
    }

    /**
     * Disallow cloning of the `RH` class.
     */
    private function __clone()
    {
    }

    /**
     * Retrieve a singleton instance of a class, or create it if it does not
     * exist.
     *
     * Classes should be stored in the _lib_ directory, and accessed based on
     * their path. All classes should have the file extension _.php_
     *
     * For example, the class `ExampleClass`, in the namespace `ExampleNS`
     * should be stored in _lib/ExampleNS/ExampleClass.php_ and accessed
     * using `I::i ()->examplens_exampleclass`
     *
     * @param string $className Class to retrieve instance of.
     * @return Object Instance of the desired class.
     */
    public function __get($className)
    {
        return self::$className ();
    }

    /**
     * @return RH The Singleton instance of `RH`
     */
    public static function i()
    {
        if (\is_null(static::$instance)) {
            static::$instance = new static;
        }
        return self::$instance;
    }

    /**
     * Magic method for constructing classes.
     *
     * If a class extends Singleton, and it already is instantiated, the
     * existing instance will be returned. Otherwise a new instance is created
     * and $arguments are passed to the constructor.
     *
     * @param string $name Name of the class, with underscores instead of
     *  backslashes for namespaces.
     * @param mixed[] $arguments Arguments to pass to the constructor, only
     *  used if the class is being instantiated.
     */
    public static function __callStatic($name, $arguments = array ())
    {
        if (isset(self::$objects[$name])) {
            return self::$objects[$name];
        }

        $className = \str_replace('_', '\\', $name);
        $instance = new $className ($arguments);

        if ($instance instanceof \RH\Singleton) {
            self::$objects[$name] = $instance;
        }

        return $instance;
    }
}
