<?php
require_once "./functions/loghandler.php";
require_once "./functions/authfunctions.php";
require_once  "./functions/databaseFunctions.php";
require_once "./functions/templateFunctions.php";

$view = "l";

function startSite() 
{
    try 
    {
        $page = getValueFromArray("page", $_GET, "Home");
        $action = getValueFromArray("action", $_GET, "Show");

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
    if(!strpos($action, '.php'))
    {
        $action .= ".php";
    }

    if(!file_exists($path = "./resources/views/$page/$action")) 
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

function getFooter()
{
    return getViewPage("../layouts", "footer.php");
}

function getValueFromArray($key, $array, $defaultValue) 
{
    return isset($array[$key]) ? $array[$key] : $defaultValue;
}

const LVL_NAV = 0;
const LVL_CAT = 1;

function setWhenActive($tabname, $lvl)
{
    $condition = false;

    if($lvl == LVL_CAT) 
    {
        $categoryget = getValueFromArray("category", $_GET, "");

        $condition = strtolower($categoryget) == strtolower($tabname);
    } else if ($lvl == LVL_NAV) {

        $page = getValueFromArray("page", $_GET, "");
        $action = getValueFromArray("action", $_GET, "");

        if(strpos($tabname, ".") > 0) {
            $tabnames = explode(".", $tabname);

            $condition = strtolower($page) == strtolower($tabnames[0]) && strtolower($action) == strtolower($tabnames[1]);

        } else {
            $condition = strtolower($page) == strtolower($tabname);
        }
    }

    return $condition ? "active" : "";
}

function getSearchResult($query)
{
    if (is_numeric($query) && strlen($query) > 4) {
        return selectProduct($query, false);
    } else {
        return selectProductsLike($query);
    }

    return "";
}

function getDiscount($price, $specialDeal) 
{
    $newPrice = $price;
    if($specialDeal["DiscountAmount"] != 0) {
        $newPrice -= $specialDeal["DiscountAmount"];
    } else if ($specialDeal["DiscountPercentage"]) {
        $newPrice *=  1 - ($specialDeal["DiscountPercentage"]/100);
    }

    return round($newPrice, 2);
}

function prepareCart() {
    if(!is_array($_SESSION["Cart"])) {
        $_SESSION["Cart"] = [];
    }

    if(isset($_POST["AddToCart"]) && isset($_POST["amount"]) && isset($_POST["productID"])) {
        if($_POST["amount"] > 0) {
            addToCart($_POST["productID"], $_POST["amount"]);
        } else {
            removeFromCart($_POST["productID"], $_POST["amount"]);
        }
    }
}

function addToCart($productID, $amount) {
    $index = in_array_r($productID, $_SESSION["Cart"]);
    if($index === false) {
        array_push($_SESSION["Cart"], ["id" => $productID, "amount" => $amount]);
    } else {
        $_SESSION["Cart"][$index]["amount"] += $amount;
    }
}

function removeFromCart($productID, $amount) {
    $index = in_array_r($productID, $_SESSION["Cart"]);

    if($index === true) {
        $storedAmount = $_SESSION["Cart"][$index]["amount"];
        if($storedAmount <= $amount) {
            unset($_SESSION["Cart"][$index]);
        } else {
            $_SESSION["Cart"][$index]["amount"] -= $amount;
        } 
    } 
}

function in_array_r($needle, $haystack, $strict = false) {
    for ($i = 0; $i < count($haystack); $i++) {
        if($haystack[$i]["id"] == $needle) {
            return true;
        }
    }

    return false;
}