<?php
namespace App\Core;

use App\Helpers\Utility;
use App\Controllers;
use App\Enum\MessageCodes;
use App\Enum\ErrorPages;

/**
 * class to get the mvc of the website
 */
class Site
{
	/**
	 * method to check if the given controller and the given method of that controller
	 * and making the controller usefull in $view
     * @return The controller;
	 */
	protected function getController()
	{
	    try
        {
            //
            // Get the controller by the get value controller.
            //
            $controller = ucfirst($_GET['controller']).'Controller';

            //
            // Get the action method by the get value action.
            //
            $tryMethod = 'get'.ucfirst($_GET['action']);
            //
            // Check if given controller exists.
            //
            if (!file_exists($file = '../App/Controllers/'.$controller.'.php'))
            {                
                throw new SiteException(MessageCodes::wwisie001, "given file '{$file}' does not exist");
            }
            else
            {
                //
                // Get the controller via namespaces.
                //
                $controller = "App\\Controllers\\{$controller}";
                $obj = new $controller();

                //
                // Check if the given method exists in the existing given controller.
                //
                if (!method_exists($controller, $tryMethod))
                {
                    throw new SiteException(MessageCodes::wwisie002, "given action '{$tryMethod}' does not exist in class '{$controller}'.");                    
                }
                else
                {
                    //
                    // Creating a reflection method to check if the existing given method is public.
                    //
                    $reflection = new \ReflectionMethod($controller, $tryMethod);

                    if (!$reflection->isPublic())
                    {
                        throw new SiteException(MessageCodes::wwisie003, "given action does not exist as public action");
                    }

                    $method = $tryMethod;
                }

                $obj->$method();

                //
                // Set the object in global variabel so it can be used in the view
                //
                return $obj;
            
            }
        }
        catch(\Exception $ex)
        {
        	$this->HandleUnexpectedException($ex, false);
            return null;
        }

	}

	/**
	 * Method to get the view.
     * @return The view.
	 */
	protected function getView()
	{	
		try
		{
			//
			// Checking if the requested view exists.
			//
			if (file_exists($path = "../resources/views/". ucfirst( $_GET['controller'] ). "/".ucfirst($_GET['action']).".phtml"))
			{			
				//
				// Load view.
				//	
				ob_start();
				$file = file_get_contents($path);
				eval("?>{$file}");
				return ob_get_clean();
			}
			else
			{
				throw new SiteException(MessageCodes::wwisie004, "The view '" . ucfirst($_GET['action']) . "' does not exist");			
			}
        }
        catch(\Exception $ex)
        {
            $this->HandleUnexpectedException($ex, false);
            return $this->getErrorPage(ErrorPages::Error404);
        } 
	}

    /**
     * Method to get the error page.
     * @return The error page.
     */
	protected function getErrorPage($error)
	{
		ob_start();
		$file = file_get_contents("../resources/views/Error Pages/{$error}.phtml");
		eval("?>{$file}");
		return ob_get_clean();

    }   

	/**
	 * Handles the unexpected exception.
	 */
	private function HandleUnexpectedException(\Exception $ex, bool $showErrorToUser)
	{
		Utility::HandleUnexpectedException($ex, $showErrorToUser);
	}
}