<?php
namespace App\Core;

use App\Core\NavigationBar;
use App\Enum\{ MessageCodes, ErrorPages };
use App\Models\ViewBag;
use App\Helpers\LoginHelper;


/**
 * Class mvc
 * this class is used to load the page
 * @see App\Core\NavigationBar
 * @since 30-03-2017
 * @version 1.2
 * @author R Haan
 */
class SiteController extends Site
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
			// if $_GET values are empty, set default
			SetWhenEmpty($_GET['controller'], "home");
	        SetWhenEmpty($_GET['action'], "show");
			
	        //
	        // loading the layout first.
	        //
	        $this->loadLayout();

	        //
	        // get the navigation bar.
	        //
	        $this->loadNavigationBar();

	        //
	        // Get the login class.
	        //
	        $this->loadAuth();

			//
			// Get the needed CSS and JS files.
			//
			$this->loadStyleSheets();
			$this->loadJavascript();

			if(LoginHelper::IsLoggedIn() && $_GET['controller'] == $this->adminPage)
			{
				$this->viewBag->content = $this->getErrorPage(ErrorPages::Unauthorized);				
			}
			else if (!LoginHelper::IsLoggedIn() && in_array($_GET['controller'], $this->authorizedPages))
			{
				$this->viewBag->content = $this->getErrorPage(ErrorPages::Unauthorized);
			}
			else
			{
				//
				// Get the needed controller.
				// 
				$this->viewBag->obj = $this->getController();	

				$content = $this->getView();

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
	        	throw new SiteException(MessageCodes::rhascr001, "File '{$file}' does not exist.");	        	
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
	 * method to get the navigation bar
	 */
	private function loadNavigationBar()
	{		
    	try
    	{
			$this->viewBag->nav = (new NavigationBar())->navShow();
		}
		catch(Exception $ex)
		{
        	$this->HandleUnexpectedException($ex, false);
		}
	}

	/**
	 * method to get the needed css.
	 */
	private function loadStyleSheets()
	{
		$css = ucfirst($_GET['controller']);
		$this->viewBag->css = "";

		if (file_exists("./css/{$css}.css")) 
		{
			$this->viewBag->css = $css;
		}	
	}	

	/**
	 * method to get the Javascript
	 */
	private function loadJavascript()
	{
		$js = ucfirst($_GET['controller']);
		$this->viewBag->js = "";

		if (file_exists($path = "./js/{$js}.js")) 
		{
			$this->viewBag->js = $path;
		}	
	}

	/**
	 * method to get the layout
	 */
	private function loadLayout()
	{
		$chozen = "Grid";
		$cssClass = "gridbox";

		$this->viewBag->cssClass = $cssClass;
		$this->viewBag->layout = $chozen;
	}		

	private function SetWhenEmpty($checkValue, $setValue) {
		if ( empty($checkValue) )
		{
			return "home";
		}

		return $checkValue;
	}
} 