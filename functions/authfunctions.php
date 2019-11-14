<?php
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

function isAuthorized(): bool
{
    return isset($_SESSION[IS_AUTHORIZED]) && $_SESSION[IS_AUTHORIZED];    
}

function logOn()
{
    if(!(isset($_POST["userName"]) && isset($_POST["password"]))// als de credentials niet bestaan
        && empty($_POST["userName"]) || empty($_POST["password"]))// of leeg zijn
    { 
        return "Vul alstublieft de velden in!";
    }
    else
    {
        if(checkCredentials($_POST["userName"], $_POST["password"])) {        
            $_SESSION[IS_AUTHORIZED] = true;
            $_SESSION["userName"] = $_POST["userName"];

            return "ingelogd!";
        }
    }
}

function logOff() {
    session_destroy();
}
