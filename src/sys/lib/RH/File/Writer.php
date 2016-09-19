<?php

/**
* Research Highlights engine
*
* Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
* See LICENCE for legal information.
*/

namespace RH\File;

/**
* Helper class for writing files.
*
* @author Martin Porcheron <martin@porcheron.uk>
*/
class Writer implements \RH\Singleton
{
    /**
    * Write a condensed single-file CSV data stream. The file must have a header
    * row. Rows can be excluded from being read by starting with a `#`.
    *
    * See {@see #RH\File\Writer::read()} for information on header structure.
    *
    * @param string $file Path to file to open
    * @param \RH\AbstractModel $model Data to write back to the permanent model.
    * @throws \RH\Error\Configuration if the file does not exist
    * @return boolean `true` if all data written in from the file.
    */
    public function write($file, \RH\Model\AbstractModel $model)
    {
        $contents = @file_get_contents($file);
        if ($contents === false) {
            throw new \RH\Error\Configuration('Could not read ' . $file);
        }

        $backup = $file .'.'. date('U') . '.bck';
        if (@file_put_contents($backup, $contents) === false) {
            throw new \RH\Error\Configuration('Could not backup ' . $file);
        }
        @\chmod($backup, 0775);

        $handle = @\fopen($file, 'r+');
        if ($handle === false) {
            throw new \RH\Error\Configuration('Could not open ' . $file .' for reading');
        }

        $headerLine = self::readLine($handle);
        $fileHeader = new \RH\File\Header($headerLine);
        @\fclose($handle);

        $write = $headerLine ."\n";
        foreach ($model->toArray() as $data) {
            $addCommma = false;
            
            foreach ($fileHeader->getAssocArray() as $colName => $colData) {
                $colType = $colData->type;

                if ($addCommma) {
                    $write .= ',';
                }

                if (isset($data[$colName])) {
                    $write .= self::valToStr($colType, $data[$colName]);
                }
 
                $addCommma = true;
            }
            
            $write .= "\n";
        }

        if (@file_put_contents($file, $write) === false) {
            throw new \RH\Error\Configuration('Could not save new file ' . $file);
        }

        return true;
    }

    /**
     * Convert the internal type to the CSV-expected format.
     *
     * @param int $colType Column type to convert to
     * @param mixed $value Value to convert
     * @return string Converted value
     */
    private static function valToStr($colType, $value)
    {
        if ($colType === \RH\File\ColumnType::BOOL) {
            return $value === true ? '1' : '0';
        } else {
            return $value;
        }
    }

    /**
    * Read a line of text from a file handle.
    *
    * @param File $handle File handle
    * @return string|false
    */
    private static function readLine(&$handle)
    {
        $line = \fgets($handle);
        if ($line !== false) {
            return \str_replace(array ("\r\n", "\n", "\r"), ' ', $line);
        }
        return false;
    }
}
