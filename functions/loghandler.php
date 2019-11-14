<?php
const LOG_FILE_PATH = "../config/log.txt";

/**
 * Reads the log file.
 */
function getFile()
{
    if (file_exists(LOG_FILE_PATH)) 
    {
        return file_get_contents(LOG_FILE_PATH);
    }

    return "";
}

/**
 * Writes to the log file.
 * @param $text, the text to write.
 */
function writeToLogFile(string $text)
{
    $now = date("d-m-Y H:i:s");
    // Open the file to get existing content.
    $content = getFile() . "[{$now}]\t{$text}\n\n";
    // Write the contents back to the file.
    writeToFile($content);
}

function writeToFile(string $text) 
{
    if (!file_exists(LOG_FILE_PATH)) 
    {
        createFile();
    }
    
    file_put_contents(LOG_FILE_PATH, $text);
}

function createFile() 
{
    $file = fopen(LOG_FILE_PATH, "w");
    fwrite($file, "");
    fclose($file);
}

/**
 * Handles the Unexpected exception and writes this error to the log.
 * @param $ex, the exception
 * @param $showErrorToUser, when true, the error will be shown to the user, false otherwise.
 */
function handleUnexpectedException(\Exception $ex, bool $showErrorToUser = false)
{
    if ($showErrorToUser)
    {
        echo `<script>
                alert("{$ex->getMessage()}");
            </script>`;
    }

    writeToLogFile("{$ex->getMessage()}\t{$ex->getTraceAsString()}\n\n");
}