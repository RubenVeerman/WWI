<?php
namespace App\Core;

use App\Core\Database;
use App\Helpers\{ NavigationHelper as navhelper, Utility, LoginHelper };

/**
 * Class navMenu
 * @see db
 * @since 30-03-2017
 * @version 1.0
 * @author R Haan
 */
class NavigationBar
{
	private $_array, $_out = '', $Navbar, $newsSub, $link, $log, $gallerysub, $dropdown, $click;

    /**
     * Getting the navigation menu
     * @return string
     */
	public function navShow()
	{	
		try
		{
			$this->db = Database::getConnection();
	        $this->_array = $this->db->queryResultsToArray("SELECT * FROM `" . navhelper::getSiteUsers() . "`");

			if (LoginHelper::isLoggedIn()) 
			{
				$this->log = "Account - {$_SESSION["username"]}";
				$this->link = navhelper::getUserPage();
				
				$this->click = '';
				$this->newsSub = "";
				$this->dropdown = "";

	            if ((new Users())->isValid()) 
				{
					$this->dropdown = 
					[
						[
							"href"=> navhelper::getAdminPage(), 
							"label" => "Beheer"
						]
					];
				}		
			}
			else
			{
		        $this->link = '#';
				$this->click = ' onclick="fOpenDialog()"';
				$this->log = "Log in";

				$this->dropdown = 
				[
					[
						"href"=> navhelper::getRegisterPage(), 
						"label" => "Registreren"
					]
				];
				$this->gallerysub ="";
				$this->newsSub = "";
			}
			
			$this->getAttributes();
			$this->_out = $this->BuildNavigationBar();
					
			return $this->_out;
		}
		catch(\Exception $ex)
		{
			$this->HandleUnexpectedException($ex);
		}
	}

	public function BuildNavigationBar()
	{
		try
		{
			$out = "<ul>";
			
			foreach ($this->Navbar as $menuItem) 
			{	
				$out .= "<li><a href=\"{$menuItem["href"]}\"";

				if ( array_key_exists("onclick", $menuItem) ) 
				{
					$out .= $menuItem["onclick"];	
				}

				$out .= "> {$menuItem["label"]}</a>";

				if (is_array($menuItem["sub"])) 
				{
					$out .= "<ul>";

					foreach ($menuItem["sub"] as $subMenuItem) 
					{
						$out .= "<li><a href=\"{$subMenuItem["href"]}\"> {$subMenuItem["label"]}</a>";
					}

					$out .= "</ul>";
				}

				$out .= "</li>";		
			}

			$out .= "</ul>";

			return $out;
		}
		catch(\Exception $ex)
		{
			$this->HandleUnexpectedException($ex);
		}
	}

	private function getAttributes()
	{

		try
		{
			$this->Navbar = 
			[
				["href" => navhelper::getHomePage(), "label" => "Hoofdpagina", "sub" => ""],


				[
					"href" => $this->link,
					"onclick" => $this->click, 
					"label" => $this->log,
					"sub" => $this->dropdown
				]
			];
		}
		catch(\Exception $ex)
		{
			$this->HandleUnexpectedException($ex);
		}
	}

	/**
	 * Handles the unexpected exception.
	 */
	private function HandleUnexpectedException(\Exception $ex, bool $showErrorToUser = false)
	{
		Utility::HandleUnexpectedException($ex, $showErrorToUser);
	}
}
