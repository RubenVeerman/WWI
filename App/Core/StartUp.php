<?php
namespace App\Core;

use App\Enum\{ MessageCodes, ErrorPages };
use App\Models\ViewBag;
use App\Helpers\{ LoginHelper, Utility };

/**
 * Class StartUp will start the site and handle the start.
 * @since 30-03-2017
 * @version 1.2
 * @author R Haan
 */
class StartUp extends StartUpBase
{
	protected $viewBag;
	protected $authorizedPages;
	protected $isOnLocalHost;
	protected $adminPage = "admin";

	public function __construct()
	{
		// define viewBag as object
		$this->viewBag = new ViewBag();
		
		$this->viewBag->SetRootPath();
		
		$this->authorizedPages = ["gallery", "user", $this->adminPage];
	}

	public function loadSiteData()
	{	
		try
		{
			// if $_GET values are empty, set default values
			$controllerName = $this->SetDefaultWhenEmpty($_GET['controller'] ?? "", "home");
	        $actionName = $this->SetDefaultWhenEmpty($_GET['action'] ?? "", "show");
			
	        //
	        // loading the layout first.
	        //
	        $this->getLayout();

	        //
	        // Get the login class.
	        //
	        $this->loadAuth();

			//
			// Get the needed CSS and JS files.
			//
			$this->loadStyleSheets($controllerName);
			$this->loadJavascript($controllerName);

			if(LoginHelper::IsLoggedIn() && $controllerName == $this->adminPage)
			{
				$this->viewBag->content = $this->getErrorPage(ErrorPages::Unauthorized);				
			}
			else if (!LoginHelper::IsLoggedIn() && in_array($controllerName, $this->authorizedPages))
			{
				$this->viewBag->content = $this->getErrorPage(ErrorPages::Unauthorized);
			}
			else
			{
				//
				// Get the needed controller.
				// 
				$this->viewBag->obj = $this->getController($controllerName, $actionName);	

				$content = $this->getView($controllerName, $actionName);

				if (empty($content)) 
				{
					$content = $this->getErrorPage(ErrorPages::Error404);
				}

				$this->viewBag->content = $content;
			}

			//
			// return the layout page.
			//
	        ob_start();
	        if (file_exists($file = "../resources/layout/{$this->viewBag->layout}/layout.phtml")) 
	        {
	        	$layout = file_get_contents($file);
	        }
	        else
	        {
	        	throw new SiteException(MessageCodes::wwiscr001, "File '{$file}' does not exist.");	        	
	        }
	        eval("?>{$layout}");
	        return ob_get_clean();
	    }
		catch(\Exception $ex)
		{
        	$this->HandleUnexpectedException($ex, false);
		}
	}

	/**
	 * method to get the login class
	 */
	private function loadAuth()
	{
		try
		{
			$auth = new Auth();
			$this->viewBag->mlogin = $auth->loginCheck();

			$this->viewBag->username = $auth->username;	
		}
		catch(\Exception $ex)
		{
        	$this->HandleUnexpectedException($ex, false);
		}
	}

	/**
	 * method to get the needed css.
	 */
	private function loadStyleSheets(string $filename)
	{
		$css = ucfirst($filename);
		$this->viewBag->css = "";

		if (file_exists("./css/{$css}.css")) 
		{
			$this->viewBag->css = $css;
		}	
	}	

	/**
	 * loads the needed Javascript
	 */
	private function loadJavascript(string $filename)
	{
		$js = ucfirst($filename);
		$this->viewBag->js = "";

		if (file_exists($path = "./js/{$js}.js")) 
		{
			$this->viewBag->js = $path;
		}	
	}

	/**
	 * gets the layout
	 */
	private function getLayout()
	{
		$this->viewBag->cssClass = "gridbox";
		$this->viewBag->layout = "Grid";
	}		

	/**
	 * Sets a default value when the given value is null or empty
	 * @param mixed $value, the value that will be checked.
	 * @param mixed $defaultValue, the value that will be returned if the $value is null or empty.
	 */
	private function SetDefaultWhenEmpty($value, $defaultValue) 
	{
		if (Utility::strIsNullOrEmpty($value))
		{
			return $defaultValue;
		}

		return $value;
	}
} 