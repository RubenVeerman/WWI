<?php
namespace App\Controllers;

use App\Helpers;
use App\Helpers\ChangeTextHelper;
use App\Enum\MessageCodes;
use App\Models\ROT13;

/**
 * Class HomeController
 * @see Helper
 * @see db
 * @since 30-03-2017
 * @version 1.1
 * @author R Haan
 */
class HomeController extends Controller
{
	private $conn;
	
	public $string, $out;

    /**
     * @return string
     */
	public function getShow()
	{
		try
		{
			// $int = 14;
			// $rot = ROT13::GetInstance($int);

			// $text = $rot->Encrypt(
			// 	"Uryyb Jbeyq!
			// 	yrccf rccvdrrc nvcbfd sza uv vvijkv kvok ze yvk evuvicreuj xvjtyivmve uffi uftvek mre yvk mrb jvtlizkp yvuve zj yvk wvsilriz uv knvvuv drreu mre yvk arri nv yvssve xvbfzve mffi vve ifk rcxfikzdv jlttvj dvk fgcfjjve drri rcj av uzk blek cvzve zj yvk av rc xvclbk xfvu xvurre");

			$this->string = $this->GetContent("../resources/views/Home/HomeContent.html"); 
			$array = ["God", "HEER", "Mij", "Zijn", "Mijn", "Ik", "Hij"];
			$this->out = /*$text;// =*/ ChangeTextHelper::setTag( $this->string , "b", $array );		
		}
		catch(\Exception $ex)
		{
			$this->HandleUnexpectedException($ex);
		}
	}

	/**
	 * Gets the content.
	 * @param path, The file path.
	 * @return string, the file content.
	 */
	private function GetContent(string $path)
	{
		if (!file_exists($path)) 
		{
			throw new SiteException(MessageCodes::rhahcr001 ,"Error Processing Request");			
		}

		return file_get_contents($path);
	}
}
?>