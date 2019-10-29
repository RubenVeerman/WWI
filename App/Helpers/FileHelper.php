<?php 
namespace App\Helpers;

use App\Enum\MessageCodes;

class FileHelper
{
    /**
	 * Gets the content.
	 * @param path, The file path.
	 * @return string, the file content.
	 */
	public static function GetContent(string $path)
	{
		if (!file_exists($path)) 
		{
			throw new SiteException(MessageCodes::rhafhr001 ,"File '$path' does not exists!");			
		}

		return file_get_contents($path);
	}
}
?>