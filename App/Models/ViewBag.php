<?php
namespace App\Models;

use App\Enum\Navigations;
use App\Helpers\NavigationHelper as NH;
/**
 * Object that takes all needed logic to the view.
 */
class ViewBag
{
	private const LocalHostPath = Navigations::LOCALHOST_FILEPATH;
	private const sitePath = "/";

	public $content;
	public $css;
	public $cssClass;
	public $isOnLocalHost; 
	public $js;
	public $layout;
	public $mlogin;
	public $obj;
	public $rootPath;
	public $username;

	public function SetRootPath()
	{
		$this->isOnLocalHost = NH::isOnLocalHost();

		if ($this->isOnLocalHost) 
		{
			$this->rootPath = self::LocalHostPath;
		}
		else
		{
			$this->rootPath = self::sitePath;
		}
	}

}
?>
