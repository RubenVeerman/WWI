<?php
namespace App\Helpers;

/**
 * Class Helper
 * To help other classes
 * @since 30-03-2017
 * @version 1.0
 * @author R Haan
 */
class DateHelper
{
    /**
     * Method to change de given date from US notation(yyyy-mm-dd) to EU(dd-mm-yyyy)
     * @param $dateUS
     * @return mixed
     */
	public static function dateUStoEU(string $dateUS)
	{
		$patterns = ['/^(19|20)(\d{2})-(\d{1,2})-(\d{1,2})$/'];
		$replace  = ['$4-$3-$1$2'];

		return preg_replace($patterns, $replace, $dateUS);
	}

    /** 
	* formats the date passed into format required by 'datetime' attribute of <date> tag
	* if no intDate supplied, uses current date.
	* @param string $date
	* @return string
	*/
	public static function getDateTimeValue(string $date ) 	
	{
  		$datetime = new \DateTime( $date, new \DateTimeZone( 'Europe/Amsterdam' ) );

  		$out = $datetime->format('d/m/Y');
  		
		return str_replace("/", "-", $out);
	
	}
}
?>