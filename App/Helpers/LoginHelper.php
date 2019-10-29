<?php
namespace App\Helpers;

/**
 * Class Helper
 * To help other classes
 * @since 21-04-2017
 * @version 1.0
 * @author R Haan
 */
class LoginHelper
{
	/**
	 * Checks if user is logged in.
	 * @returns Boolean if user is logged in, return true, false otherwise.
	 */
	public static function IsLoggedIn()
	{
		if (isset($_SESSION['login']) && $_SESSION['login'] == true) 
		{
			return true;	
		}
		return false;
	}

	/**
     * check if the two parameters are equal to eachother
     * @param $post
     * @param $user
     * @return bool
     */
	public static function UserCheck(string $post, string $user)
	{
		//
		// check if the post user is equal to the user in the database
		//
		if ($post == $user) 
		{
			return true;
		}
		else
		{
			return false;
		}		
	}

    /**
     * giving an message
     * @return string
     */
	public static function notLoggedInMessage()
	{
		return '<h1>U moet ingelogd zijn om deze pagina te kunnen bekijken<h1>';
	}

    /**
     * giving an message
     * @return string
     */
	public static function noAdminMessage()
	{
		return'<h1>U heeft geen rechten om deze pagina te kunnen bekijken</h1>';
	}
}
?>