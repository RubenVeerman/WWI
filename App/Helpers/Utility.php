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
     * @param $showErrorToUser, decides if the user will see the error.
     */
	public static function HandleUnexpectedException(\Exception $ex, $showErrorToUser = true)
	{
	    if ($showErrorToUser)
	    {
            $out = '<script language="javascript">';
            $out .= "alert(\"" . $ex->getMessage() . "\");";
            $out .= '</script>';
            echo $out;
        }

        $textToLog = "{$ex->getMessage()}\t{$ex->getTraceAsString()}\n\n";
	    
        LogHelper::WriteToLogFile($textToLog);
	}
}
?>