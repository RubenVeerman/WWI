<?php
require_once "./functions/loghandler.php";
require_once "./functions/authfunctions.php";

$view = "l";

function startSite() 
{
    try 
    {
        $page = getValueFromArray("page", $_GET) ?? "Home";
        $action = getValueFromArray("action", $_GET) ?? "Show";

        return getView($page, $action);
    }
    catch(Exception $ex) 
    {
        HandleUnexpectedException($ex);
    }
}

function getLayout($file) 
{
    ob_start();
    if (file_exists($file = "./resources/layouts/{$file}.php")) 
    {
        $layout = file_get_contents($file);
    }
    else
    {
        throw new Exception("File '{$file}' does not exist.");	        	
    }
    eval("?>{$layout}");
    return ob_get_clean();
}

function getView($page, $action) 
{
    $view = getViewPage($page, $action);

    if(empty($view)) 
    {
        $view = getViewPage("error_pages", "error404");
    }

    return $view;
}

function getViewPage($page, $action)
{
    if(!file_exists($path = "./resources/views/$page/$action.php")) 
    {
        return null;
    } 
    else
    {
        ob_start();
        $file = file_get_contents($path);
        eval("?>{$file}");        
        return ob_get_clean();
    }
}

function getValueFromArray($key, $array) 
{
    return isset($array[$key]) ? $array[$key] : null;
}

function setWhenActive($tabname)
{
    $page = getValueFromArray("page", $_GET) ?? "";

    return strtolower($page) == strtolower($tabname);
}