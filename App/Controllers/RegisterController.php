<?php
namespace App\Controllers;

use App\Core\Database;
use App\Helpers;
use App\Helpers\NavigationHelper as nav;

/**
 * Class RegisterController
 * @see Database
 * @since 30-03-2017
 * @version 1.0
 * @author R Haan
 */
class RegisterController extends Controller
{
    private $database;
    
    public $data;
    public $out;

    public function __construct()
    {
        $this->database = Database::getConnection();
        $this->users = $this->database->queryResultsToArray("SELECT * FROM `" . nav::getSiteUsers() . "`");
    }

    /**
     * creating account
     * @return string
     */
    public function getShow()
    {
        if (isset($_POST['knop'])) 
        {
            $bool = false;

            $this->checkAccount();                              
        }
    }

    private function checkAccount()
    {
        if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) 
        {
            if ( !$this->usernameExists($_POST['username']) && !$this->emailExists($_POST['email'])) 
            {
                $this->out = "";

                $regexEMail = '/@.+\./';
                $regexUser = '/^[A-z\d_]{4,20}$/i';

                if (!preg_match($regexEMail, $_POST['email']) && !preg_match($regexUser, $_POST['username'])) 
                {
                    if (!preg_match($regexEMail, $_POST['email'])) 
                    {
                        $this->out = "E-Mail ";
                    }
                    if (!preg_match($regexUser, $_POST['username'])) 
                    {
                        $this->out .= "and User";
                    }
                    $this->out .= " is invalid</p>";
                    $_POST['username'] = "";
                    $_POST['email'] = "";
                }
                else 
                {
                    $this->createAccount();                       
                }  
            } 
            else
            {
                if ($this->usernameExists($_POST['username'])) 
                {
                    $this->out = "Gebruikersnaam bestaat al";
                    $usernameExists = true;
                }

                if ($this->emailExists($_POST['email'])) 
                {
                    if ($usernameExists) 
                    {
                        $this->out .= ", ";
                    }
                    
                    $this->out = "E-Mail is al geregistreerd.";
                }
            }    
        } 
    }

    private function createAccount()
    {
        $this->data = 
        [
            $_POST['username'],
            password_hash($_POST['password'], PASSWORD_BCRYPT),
            $_POST['email'],
            $_POST['firstname'],
            $_POST['tv'],
            $_POST['lastname'],
            $_POST['birthday'],
            $_POST['adres'],
            $_POST['HuisNr'],
            $_POST['city'],
            $_POST['postcode'],
            $_POST['telefoonnummer'] 
        ];
        $this->out = "Het registreren van het account is fout gegaan, probeer het later nog eens.";

        if (  $this->database->registerAcc($this->data)  ) 
        {
            header("Location: " . nav::HOME_PAGE);
        } 
    }

    private function usernameExists($post)
    {
        foreach ($this->users as $user) 
        {
            if ($post == $user['username']) 
            {
                return true;
            }
        }

        return false;
    }

    private function emailExists($post)
    {
        foreach ($this->users as $user) 
        {
            if ($post == $user['email']) 
            {
                return true;
            }
        }

        return false;
    }

	/**
	 * Handles the unexpected exception.
	 */
	private function HandleUnexpectedException(\Exception $ex, bool $showErrorToUser)
	{
		Helpers\Utility::HandleUnexpectedException($ex, $showErrorToUser);
	}
}



