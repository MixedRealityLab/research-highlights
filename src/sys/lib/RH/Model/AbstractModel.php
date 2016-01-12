<?php

/**
* Research Highlights engine
*
* Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
* See LICENCE for legal information.
*/

namespace RH\Model;

/**
* Data storage model.
*
* @author Martin Porcheron <martin@porcheron.uk>
*/
abstract class AbstractModel extends \RH\RecursiveArrayObject
{

    /** @var bool Create field when they are retrieved */
    protected $createOnGet = false;

    /** @var string Cache file name */
    protected $cacheFile = null;

    /** @var int Cache expiry time */
    protected $cacheTime = CACHE_GENERAL;

    /** @var int `1` if a cache of this model exists, `-1` if not */
    private $hasCache = 0;

    /** @var bool Save the Model to the cache on destruction */
    protected $saveOnDestruct = false;

    /**
    * Construct the data object, with initial data values, if any.
    *
    * @param mixed[] $data Data to construct initial object with
    * @return \RH\Model\AbstractModel
    */
    public function __construct($data = array ())
    {
        return parent::__construct($data);
    }

    /**
    * Construct the data object, with initial data values, if any.
    *
    * @param mixed[] $data Data to construct initial object with
    * @return \RH\Model\AbstractModel
    */
    public function __destruct()
    {
        if ($this->saveOnDestruct && $this->cacheTime > 0) {
            $this->saveCache();
        }
    }

    /**
    * Retrieve the value of a property.
    *
    * @param string $key Name of the property to retrieve
    * @throws \RH\Error\NoField if the property not found
    */
    public function __get($key)
    {
        try {
            return parent::__get($key);
        } catch (\InvalidArgumentException $e) {
            if ($this->createOnGet) {
                parent::offsetSet($key, $this->newChild(array ()));
                return parent::__get($key);
            } else {
                throw new \RH\Error\NoField($key, get_called_class());
            }
        }
    }

    /**
    * Determines whether cache functionality has been set for this object
    *
    * @return boolean
    */
    public function cacheIsSet()
    {
        return $this->cacheTime > 0 && !\is_null($this->cacheFile);
    }

    /**
    * Set the Cache properties for this model.
    *
    * @param int $time Cache expiry time (set to 0 to disable cache)
    * @param string $file Filename of the cache
    * @param bool $saveOnDestruct Save the Model to the cache on destruction
    * @return \RH\Model\AbstractModel
    */
    public function setCache($time, $file = null, $saveOnDestruct = false)
    {
        $this->cacheTime = $time;
        $this->cacheFile = $file;
        $this->saveOnDestruct = $saveOnDestruct;
        return $this;
    }

    /**
    * Does a cache exist for this Model?
    *
    * @return bool
    */
    public function hasCache()
    {
        if (\defined('NO_CACHE')) {
            return false;
        } elseif ($this->hasCache !== 0) {
            return $this->hasCache > 0;
        } elseif (!\is_null($this->cacheFile)) {
            $file = DIR_CAC . '/' . $this->cacheFile;
            \clearstatcache(true, $file);
            $bool = \is_file($file) && \filemtime($file) + CACHE_GENERAL > \date('U');
            $this->hasCache = $bool ? 1 : -1;
            return $bool;
        }

        return false;
    }

    /**
    * Load a Model from the cache.
    *
    * @return \RH\Model\AbstractModel
    */
    public function loadCache()
    {
        if ($this->hasCache()) {
            $file = DIR_CAC . '/' . $this->cacheFile;
            $str = @\file_get_contents($file);
            if (!empty($str)) {
                $mModel = new static ();
                $mModel->unserialize($str);
                $this->merge($mModel);
            }
        }

        return $this;
    }

    /**
    * Save this model to the cache.
    *
    * @return \RH\Model\AbstractModel
    */
    public function saveCache()
    {
        if ($this->cacheTime > 0 && !\is_null($this->cacheFile)) {
            $file = DIR_CAC . '/' . $this->cacheFile;

            if (\is_dir(DIR_CAC) !== false) {
                @\mkdir(DIR_CAC, 0777, true);
            }

            @\file_put_contents($file, $this->serialize());
            @\chmod($file, 0777);
        }

        return $this;
    }

    /**
    * Clear the cache for this model;
    *
    * @return \RH\Model\AbstractModel
    */
    public function clearCache()
    {
        if (!\is_null($this->cacheFile)) {
            $file = DIR_CAC . '/' . $this->cacheFile;
            @\unlink($file);
        }

        return $this;
    }

    /**
    * Fill this model with data from a string, with a numerical offset.
    *
    * @param string $str String to extract data from
    * @param string $sep How to separate the data in the field.
    * @throws \RH\Error\NoField if the property not found
    * @return \RH\Model\AbstractModel
    */
    public function fromString($str, $sep = ',')
    {
        $data = \preg_split("/$sep/", \trim($str), null, PREG_SPLIT_NO_EMPTY);
        foreach ($data as $k => $v) {
            $k = \trim($k);
            $v = \trim($v);
            $this->$k = $v;
        }
    }

    /**
    * Convert an array of `AbstractModel` objects to a 2D array.
    *
    * @param \RH\Model\AbstractModel[] Objects to convert to arrays
    * @return mixed[][] 2D array of data
    */
    public static function toArrays($data)
    {
        $result = array ();
        foreach ($data as $k => $v) {
            $result[$k] = $v->toArray();
        }
        return $result;
    }

    /**
    * Convert multiple `AbstractModel` objects to a merged arrays.
    *
    * @return \RH\Model\AbstractModel[] Combined arrays
    */
    public static function mergeArrays()
    {
        $args = \func_get_args();
        $arrArgs = array ();
        foreach ($args as $arg) {
            if ($arg instanceof AbstractModel) {
                $arrArgs = \array_merge($arrArgs, $arg->toArray());
            } elseif (\is_array($arg)) {
                $arrArgs = \array_merge($arrArgs, $arg);
            }
        }

        return $arrArgs;
    }

    /**
    * Convert multiple `AbstractModel` objects to a merged JSON string.
    *
    * @return string JSON string
    */
    public static function mergeJson()
    {
        $args = \func_get_args();
        $arrArgs = array ();
        foreach ($args as $arg) {
            if ($arg instanceof AbstractModel) {
                $arrArgs = \array_merge($arrArgs, $arg->toArray());
            } elseif (\is_array($arg)) {
                $arrArgs = \array_merge($arrArgs, $arg);
            }
        }

        return \json_encode($arrArgs);
    }

    /**
    * Convert a 2D array to an array of `AbstractModel` objects.
    *
    * @param mixed[][] Arrays to convert to array of AbstractModels
    * @return \RH\Model\AbstractModel[] Array of data
    */
    public static function fromArrays(&$data)
    {
        $res = array ();
        $class = static::className();
        foreach ($data as $k => $v) {
            $res[$k] = new $class ($v);
        }
        return $res;
    }

    /**
    * Append a second AbstractModel object, adds values with new numerical
    * offsets.
    *
    * @param \RH\Model\AbstractModel|mixed Another AbstractModel object or array to
    *   append into this one
    * @return \RH\Model\AbstractModel this object
    */
    public function append($data)
    {
        if (is_array($data) || $data instanceof AbstractModel) {
            foreach ($data as $k => $v) {
                $k = $this->count();
                $this->$k = $v;
            }
        } else {
            parent::append($data);
        }

        return $this;
    }

    /**
    * Merge a second AbstractModel object, overwriting any existing values;
    *
    * @param \RH\Model\AbstractModel|mixed[] Another AbstractModel object or
    *   array to merge into this one
    * @return \RH\Model\AbstractModel this object
    */
    public function merge($data)
    {
        foreach ($data as $k => $v) {
            $this->__set($k, $v);
        }

        return $this;
    }

    /**
    * Filter this dataset.
    *
    * @param function $filterFn Filter function that takes one parameter (the
    *   data property) and returns a boolean value.
    * @return \RH\Model\AbstractModel
    */
    public function filter(&$filterFn)
    {
        $unset = array ();
        $i = $this->count() - 1;

        foreach ($this as $key => $value) {
            if (!$filterFn ($value)) {
                $unset[] = $key;
            }
        }

        foreach ($unset as $index) {
            $this->offsetUnset($index);
        }

        return $this;
    }

    /**
    * @return mixed[] Data stored in an array.
    */
    public function toArray()
    {
        return $this->getArrayCopy();
    }

    /**
    * Convert this object to a JSON object.
    *
    * @return string JSON object representation of this object.
    */
    public function toJson()
    {
        return \json_encode($this);
    }

    /**
    * Convert this object to a JSON array.
    *
    * @return string JSON array representation of this object.
    */
    public function toArrayJson()
    {
        return \json_encode(\array_values($this->toArray()));
    }

    /**
     * Map an item to each key/value pair in the model.
     *
     * @param function $fn Function for mapping to the items in the model.
     * @param AbstractModel $result location for the results
     * @return AbstractModel a new model
     */
    public function map($fn, AbstractModel &$result)
    {
        foreach ($this as $key => $value) {
            $fn ($key, $value);
            $result->offsetSet($key, $value);
        }
        return $result;
    }


    /**
     * Fetch a list of the keys of the model.
     *
     * @param function $fn Function for mapping to the items in the model,
     *  takes two arguments (the key and the value); by default this
     *  returns just the key
     * @return Keys a list of the keys
     */
    public function getKeys($fn = null)
    {
        $newKey = 0;
        $keyFn = function (&$key, &$value) use (&$newKey) {
            $value = $key;
            $key = $newKey++;
        };

        return $this->map($keyFn, new Keys());
    }
}
