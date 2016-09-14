<?php

/**
* Research Highlights engine
*
* Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
* See LICENCE for legal information.
*/

namespace RH\File;

/**
* File header representation.
*
* @author Martin Porcheron <martin@porcheron.uk>
*/
class Header
{

    /** @var mixed[] Columns within the header */
    private $columns = array ();

    /** @var mixed[] Columns within the header */
    private $assocColumns = array ();

    /** @var int Which column was `str_rem`, and thus gobbles remaining cols */
    private $gobbleFrom = -1;

    /**
    * Construct the header representation, with the `string` text of the file
    * header.
    *
    * @see \RH\File\Reader::read () for formatting
    * @param string $row Header of the file.
    * @return void
    */
    public function __construct($row)
    {
        $cols = \preg_split('/,/U', \trim($row), null, PREG_SPLIT_NO_EMPTY);
        foreach ($cols as $i => $col) {
            $data = \preg_split('/:/U', \trim($col), null, PREG_SPLIT_NO_EMPTY);
            if (\count($data) !== 2) {
                throw new \RH\Error\Configuration('File has incorrect number of parameters per column (has ' .
                  count($data) .') in ' . $col);
            }
            $this->add($data[0], $data[1]);
        }
    }

    /**
    * Add a column to the header
    *
    * @see \RH\File\ColumnType for type information
    * @param string $column Column name
    * @param string $type Valid string representation of the column type
    * @return void
    */
    private function add($column, $type)
    {
        $type = \RH\File\ColumnType::fromString($type);
        $col = new \RH\File\Column($column, $type);
        $this->columns[] = $col;
        $this->assocColumns[$column] = $col;

        if ($type == \RH\File\ColumnType::LONG_STRING) {
            $this->gobbleFrom = \count($this->columns) - 1;
        }
    }

    /**
    * Get all columns' information
    *
    * @return Column[] associate array of columns.
    */
    public function getAssocArray()
    {
        return $this->assocColumns;
    }

    /**
    * Get a column's information
    *
    * @param int $id Index of the column (starting from 0), or the name.
    * @return \RH\File\Column
    */
    public function get($id)
    {
        if (is_int($id)) {
            if ($this->gobbleFrom >= 0 && $this->gobbleFrom < $id) {
                return $this->columns[$this->gobbleFrom];
            }
            
            return count($this->columns) < $id ? null : $this->columns[$id];
        } elseif (isset($this->assocColumns[$id])) {
            return $this->assocColumns[$id];
        } else {
            return null;
        }
    }

    /**
    * Convert a `string` value to the correct type for the column
    *
    * @param int $id Index of the column (starting from 0)
    * @param string $str String representation to convert
    * @return mixed Value of the correct type
    */
    public function toType($id, $str)
    {
        $col = $this->get($id);

        if (\is_null($col)) {
            return null;
        }

        return \RH\File\ColumnType::strTo($col->type, $str);
    }

    /**
    * @return \RH\File\Column[] Array of columns
    */
    public function toArray()
    {
        return $this->columns;
    }
}
