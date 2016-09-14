<?php

/**
* Research Highlights engine
*
* Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
* See LICENCE for legal information.
*/

namespace RH\Model;

/**
* Class to handle all `REQUEST` data into the website.
*
* @author Martin Porcheron <martin@porcheron.uk>
*/
class Input extends AbstractModel implements \RH\Singleton
{
    /**
     * Do not recurse on input.
     */
    protected $recurse = false;

    /**
    * Construct the `Input` handler by importing the superglobals, and then
    * destroying them. This makes this class the definitive source of input.
    */
    public function __construct()
    {
        if (!isset($_GET)) {
            $_GET = array ();
        }
        if (!isset($_POST)) {
            $_POST = array ();
        }
        if (!isset($_FILES)) {
            $_FILES = array ();
        }

        parent::__construct(\array_merge($_GET, $_POST));
        $this->offsetSet('files', $_FILES);
        unset($_GET, $_POST, $_FILES);
    }

    /**
    * Save uploaded files to a given directory
    * From http://php.net/manual/en/features.file-upload.php
    *
    * @param string $name Name of the field name
    * @param string $dir Directory to save files to
    * @return string[] The name of the file
    * @throws \RH\Error\InvalidInput if there was an error
    */
    public function upload($name, $dir)
    {
        if (!isset($this->files[$name])) {
            throw new \RH\Error\InvalidInput('Could not find files.');
        }

        $num = \count($this->files[$name]['name']);
        $names = array ();

        if (!@\is_dir($dir)) {
            @\mkdir($dir, true, 0777);
            @\chmod($dir, 0777);
        }

        $files = $this->files[$name];

        for ($i = 0; $i < $num; $i++) {
            if (!isset($files['error'][$i])  || \is_array($files['error'][$i])) {
                throw new \RH\Error\InvalidInput('Invalid parameters.');
            }

            switch ($files['error'][$i]) {
                case UPLOAD_ERR_OK:
                    break;

                case UPLOAD_ERR_NO_FILE:
                    throw new \RH\Error\InvalidInput('No file sent.');

                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RH\Error\InvalidInput('Exceeded filesize limit.');

                default:
                    throw new \RH\Error\InvalidInput('Unknown errors.');
            }

            if ($files['size'][$i] > 1000000) {
                throw new \RH\Error\InvalidInput('Exceeded filesize limit.');
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = \array_search(
                $finfo->file($files['tmp_name'][$i]),
                array (
                    'jpeg' => 'image/jpeg',
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                true
            )) {
                    throw new \RH\Error\InvalidInput('Invalid file format.');
            }

            $name = \sha1_file($files['tmp_name'][$i]) . \date('U') . '.' . $ext;

            if (!@\move_uploaded_file($files['tmp_name'][$i], $dir . '/' . $name)) {
                throw new \RH\Error\InvalidInput('Failed to move uploaded file.');
            }

            @\chmod($dir . '/' . $name, 0777);

            $names[] = $name;
        }

        return $names;
    }
}
