<?php
namespace App\Helpers;

/**
 * Class Helper
 * To help other classes
 * @since 30-03-2017
 * @version 1.0
 * @author R Haan
 */
class ChangeTextHelper
{
    /**
     * To change specific words given in the Array into the given String and choose the tag to style the words
     * bold etc.
     * @param string $text
     * @param string $tag
     * @param array $array
     * @return string
     */
	public static function setTag(string $text, string $tag, array $array )
    {
		if(empty($text) || empty($tag) || empty($array))
        $arr = explode(" ", $text);

		if(is_array($arr))
		{
			foreach ($arr as $key => $value) 
			{
				$val = rtrim($value, ",.;");
				if (in_array($val, $array)) 
				{
					$arr[$key] = "<{$tag}>{$value}</{$tag}>";
				}
			}

			return implode(" ", $arr);
		}
		
		return $text;
	}
}
?>