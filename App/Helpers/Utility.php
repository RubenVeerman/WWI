<?php
namespace App\Helpers;

/**
 * Class Utility
 * To help other classes with the Handling with Exceptions.
 * @since 16-10-2017
 * @version 1.0
 * @author R Haan
 */
class Utility
{
    /**
     * Handles the Unexpected exception and writes this error to the log.
     * @param $ex, the exception
     * @param $showErrorToUser, when true, the error will be shown to the user, false otherwise.
     */
	public static function HandleUnexpectedException(\Exception $ex, $showErrorToUser = true)
	{
	    if ($showErrorToUser)
	    {
            echo `<script>
                    alert("{$ex->getMessage()}");
                </script>`;
        }

        LogHelper::WriteToLogFile("{$ex->getMessage()}\t{$ex->getTraceAsString()}\n\n");
    }
    
    /**
     * Checks if a string is null or empty.
     * @return bool
     */
    public static function strIsNullOrEmpty(string $str) 
    {
        return $str == null || $str == "";
    }
}
?>