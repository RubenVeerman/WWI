<?php
require_once "./functions/loghandler.php";
require_once  "./functions/databaseFunctions.php";
const IS_AUTHORIZED = "isAuthorized";

function startAuth()
{
    if(isset($_POST["submit_logon"])) 
    {
        return logOn();
    } 
    else if(isset($_POST["submit_logoff"]))
    {
        return logOff();
    } 
}

function isAuthorized()
{
    return isset($_SESSION[IS_AUTHORIZED]) && $_SESSION[IS_AUTHORIZED];    
}

function logOn()
{
    if(!(isset($_POST["userName"]) && isset($_POST["password"]))// als de credentials niet bestaan
        && empty($_POST["userName"]) || empty($_POST["password"]))// of leeg zijn
    { 
        return false;
    }
    else
    {
        if(checkCredentials($_POST["userName"], $_POST["password"])) {        
            $_SESSION[IS_AUTHORIZED] = true;
            $_SESSION["userName"] = $_POST["userName"];

            return true;
        }
    }
}

function logOff() {
    session_destroy();
}
if (isset($_POST['submit_registration'])) {
    validateRegistration();
}

function validateRegistration() {
    if (!empty($_POST['email']) && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['pass1']) && !empty($_POST['pass2'])) {
        if ($_POST['pass1'] == $_POST['pass2']) {
            createCustomerAccount() ;
        }
    }
}
