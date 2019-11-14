<?php
require_once "./functions/loghandler.php";

$view = "l";

function startSite() 
{
    try 
    {
        $page = getValueFromArray($_GET["page"]);
        $action = getValueFromArray($_GET["action"]);

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
    ob_start();
    $file = file_get_contents("./resources/views/$page/$action.php");
    eval("?>{$file}");
    return ob_get_clean();
}

function getValueFromArray($value) 
{
    return isset($value) ? $value : null;
}