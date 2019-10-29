<?php
namespace App\Core;

/**
 * Gives the wanted information when Exception is thrown.
 */
class SiteException extends \Exception
{
    /**
     * Make a message code necassary.
     */
    public function __construct($code, $message, \Exception $previous = null)
    {
        $message = "'{$code}': {$message}";
        parent::__construct($message, null, $previous);
    }

    /**
     *  Custom string representation of object.
     */
    public function __toString() 
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>