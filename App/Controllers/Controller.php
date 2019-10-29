<?php
namespace App\Controllers;

class Controller
{
	public $message;
	public $title = "yeah lets go";
	
	/**
	 * Handles the unexpected exception
	 * @param ex. The exception.
	 * @param showErrorToUser. When true, show error to user, false otherwise.
	 */
	protected function HandleUnexpectedException(\Exception $ex, bool $showErrorToUser)
	{
		App\Helpers\Utility::HandleUnexpectedException($ex, $showErrorToUser);
	}
}
?>