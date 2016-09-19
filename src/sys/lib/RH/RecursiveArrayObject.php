<?php

/**
 * Recursive array object for convenient data storage.
 *
 * @author: etconsilium@github
 * @license: BSDLv2
 */

namespace RH;

/**
 * Data storage model.
 *
 * @author: etconsilium@github; Martin Porcheron <martin@porcheron.uk>
 */
class RecursiveArrayObject extends \ArrayObject
{

    /** @var bool Enable recursive object creation */
    protected $recurse = true;

    /**
     * Construct the data object, with initial data values, if any.
     *
     * @param mixed[] $data Data to construct initial object with
     * @param int $flags See {@link \ArrayObject}
     * @param string $iterator_class Class to use as iterator
     * @return New RecursiveArrayObject
     */
    public function __construct($data = null, $flags = self::ARRAY_AS_PROPS, $iterator_class = 'ArrayIterator')
    {
        foreach ($data as $k => $v) {
            $this->__set($k, $v);
        }

        return $this;
    }

    /**
     * Set the value of an property.
     *
     * @param string $key Name of the property of data to set
     * @param mixed $value Value of the property.
     * @return void
     */
    public function __set($key, $value)
    {
        if ($this->recurse && \is_array($value) || \is_object($value)) {
            $this->offsetSet($key, $this->newChild($value));
        } else {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * Retrieve the value of a property.
     *
     * @param string $key Name of the property to retrieve
     * @return mixed Value of the property
     * @throws \InvalidArgumentException if the property not found
     */
    public function __get($key)
    {
        if ($recurse) {
            if ($this->offsetExists($key)) {
                return $this->offsetGet($key);
            } elseif (\array_key_exists($key, $this)) {
                return $this[$key];
            }
        } else {
            $pos = \strpos($key, '[');

            if($pos !== false) {
                $newkey = \substr($key, 0, $pos);
                $temp = \substr($key, $pos + 1);
                $posclose = \strpos($temp, ']');

                $elem = null;
                if ($this->offsetExists($newkey)) {
                    $elem = $this->offsetGet($newkey);
                } elseif (\array_key_exists($newkey, $this)) {
                    $elem = $this[$newkey];
                }

                $key = \substr($temp, 0, $posclose) . \substr($temp, $posclose + 1);

                if (!\is_null($elem)) {
                    $parent = $elem;
                    $pos = \strpos($key, '[');

                    while ($pos !== false) {
                        $newparent = \substr($key, 0, $pos);
                        $temp = \substr($key, $pos + 1);
                        $posclose = \strpos($temp, ']');
                        $newkey = \substr($temp, 0, $posclose) . \substr($temp, $posclose + 1);

                        if (\array_key_exists($newparent, $parent)) {
                            $parent = $parent[$newparent];
                            $key = $newkey;
                            $pos = \strpos($subkey, '[');
                        } else {
                            break;
                        }
                    }
                }

                return $parent[$key];
            } elseif (\array_key_exists($key, $this)) {
                return $this[$key];
            }
        }

        throw new \InvalidArgumentException(\sprintf('No property `%s` in `%s`', $key, static::className()));
    }

    /**
     * @param string $key Property to search for in the dataset.
     * @return bool `true` if an property exists
     */
    public function __isset($key)
    {
        $parent = $this;
        $pos = \strpos($key, '[');

        while ($pos !== false) {
            $newkey = \substr($key, 0, $pos);
            $temp = \substr($key, $pos + 1);
            $posclose = \strpos($temp, ']');
            $subkey = \substr($temp, 0, $posclose) . \substr($temp, $posclose + 1);

            if (\array_key_exists($newkey, $parent)) {
                if($recurse) {
                    $parent = $parent->__get($newkey);
                } else {
                    $parent = $parent[$newkey];
                }
                $key = $subkey;
            }

            $pos = \strpos($key, '[');
        }
        
        return \array_key_exists($key, $parent);
    }

    /**
     * @param string $key Property to unset.
     * @return void
     */
    public function __unset($key)
    {
        unset($this[$key]);
    }

    /**
     * Create a new child object.
     *
     * @param mixed $value Value of the property.
     * @return Object Return a new instance of a child of the instantiated class
     */
    protected function newChild($value)
    {
        return new static ($value);
    }

    /** @return class Current class name */
    protected static function className()
    {
        return get_called_class();
    }
}
