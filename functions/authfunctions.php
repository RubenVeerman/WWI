<?php
require_once "./functions/loghandler.php";
require_once  "./functions/databaseFunctions.php";

function startAuth()
{
    if(isset($_POST["submit_logon"])) 
    {
        logOn();
    } 
    else if(isset($_POST["submit_logoff"]))
    {
        logOff();
    } 
}

function isAuthorized()
{
    return isset($_SESSION["UserName"]) && !empty($_SESSION["UserName"]);    
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
            $_SESSION["userName"] = $_POST["userName"];

            header("location: index.php");
        }
        else{
            header("location: index.php?page=auth&action=login&login=failed");
        }
    }
}

function logOff() {
    session_destroy();
    //    unset($_SESSION["UserName"]);
    header("location: index.php");
}
if (isset($_POST['submit_registration'])) {
    validateRegistration('person');
}

function validateRegistration($type) {
    if (!empty($_POST['email']) 
        && !empty($_POST['fname']) 
        && !empty($_POST['lname']) 
        && !empty($_POST['pass1']) 
        && !empty($_POST['pass2'])) {
        if ($_POST['pass1'] == $_POST['pass2']) {
            if(checkEmailIfExists($_POST['email'], 999999999999999)) {
                if($type == 'person'){
                    header("location: index.php?page=auth&action=registration&registration=failed");
                }
                else{
                    header("location: index.php?page=user&action=add&add=fail");
                }
            } else{
                createCustomerAccount($type) ;
            }
        }
    }
    else{
        if($type == 'person'){
        }
        else{
            header("location: index.php?page=user&action=add&add=failed");
        }
    }
}

