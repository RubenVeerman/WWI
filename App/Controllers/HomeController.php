<?php
namespace App\Controllers;

use App\Enum\MessageCodes;
use App\Core\SiteException;

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

			$this->out = $this->GetContent("../resources/views/Home/HomeContent.html"); 		
		}
		catch(\Exception $ex)
		{
			$this->HandleUnexpectedException($ex, false);
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
			throw new SiteException(MessageCodes::wwihcr001 ,"Error Processing Request");			
		}

		return file_get_contents($path);
	}
}
?>