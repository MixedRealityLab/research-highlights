<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Model;

/**
 * A user within the system.
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class User extends AbstractModel
{

    /**
     * Scan text for keywords that can be replaced.
     *
     * @param string $input Input to be scanned
     * @param bool $includePassword Include the password in the substitutions
     * @return string Output with the substitutions made
     */
    public function makeSubsts($input, $includePassword = false)
    {
        $k = array ('imgDir' => URI_DATA . '/' . $this->cohort . '/' .
          $this->username . '/' . $this->latestVersion .'/');
        if ($includePassword) {
            $k['password'] = $this->getPassword();
        }

        $arr = \array_merge($k, $this->getArrayCopy());
        $keys = \array_map(function ($k) {
            return '<' . $k . '>';
        }, \array_keys($arr));

        return \str_replace($keys, \array_values($arr), $input);
    }

    /**
     * Get the user's password
     *
     * @return string Password of the user
     */
    public function getPassword()
    {
        return \sha1(SALT . $this->username);
    }

    /**
     * List of possible substitutions.
     *
     * @param bool $includePassword Include the password in the substitutions
     * @return string[] List of possible substitutions
     */
    public static function substsKeys($includePassword = false)
    {
        $k = array ('<wordCount>', '<fundingStatment>', '<imgDir>');
        if ($includePassword) {
            $k[] = 'password';
        }

        $oFileReader = \I::RH_File_Reader();
        $header = $oFileReader->readHeader(DIR_USR . \RH\User::USER_FILE);
        $ret = \array_merge($k, array_map(function ($col) {
            return '<' . $col->name .'>';
        }, $header->toArray()));

        \sort($ret, SORT_STRING|SORT_NATURAL);
        return $ret;
    }
}
