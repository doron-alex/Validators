<?php


declare(strict_types=1);


namespace ADoronichev\Validators;


final class EmailValidator
{

    private string $string;

    public function __construct(string $inputString)
    {
        $this->initString($inputString);
    }

    /**
     *
     * Remove "wrong" characters
     *
     * @param string $inputString
     */
    private function initString(string $inputString): void
    {
        $inputString = trim($inputString);
        $inputString = stripslashes($inputString);
        $inputString = htmlspecialchars($inputString);
        $this->string = $inputString;
    }

    /**
     *
     * Check if brackets position is correct
     *
     * @param bool $checkMx
     * @return bool
     */
    public function isValid(bool $checkMx = false): bool
    {
        //Check if string can be e-mail
        $isValidString = filter_var($this->string, FILTER_VALIDATE_EMAIL)
            && preg_match('/@.+\./', $this->string);
        if (!$isValidString) {
            return false;
        }

        //Check if domain has mx-record
        if ($checkMx) {
            $domain = substr($this->string, strrpos($this->string, '@') + 1);
            $checkDomain = getmxrr($domain, $mxHosts);
            return $checkDomain && !empty($mxHosts);
        }

        return true;
    }

}