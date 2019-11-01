<?php
namespace App\Helpers;

use App\Enum\Navigations;

class NavigationHelper extends Navigations
{	

	public static function isOnLocalHost()
	{
		return in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
	}

	public static function getHomePage()
	{
		return self::getLink(self::HOME_PAGE);
	}
	
	public static function getRegisterPage()
	{
		return self::getLink(self::REGISTER_PAGE);		
	}

	public static function getAdminPage()
	{
		return self::getLink(self::ADMIN_PAGE);		
	}

	public static function getUserPage()
	{
		return self::getLink(self::USER_PAGE);		
	}

	public static function getSiteUsers()
	{
		return self::SITE_USERS_TABLE;
	}		

	public static function getSiteContent()
	{
		return self::SITE_CONTENT_TABLE;
	}
	

	public static function getLink(string $link)
	{
		$alink = "";

		if (self::isOnLocalHost()) 
		{
			$alink .= "/worldwideimporters/public";
		}

		$alink .= $link;

		return $alink;
	}
}