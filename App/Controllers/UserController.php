<?php
namespace App\Controllers;

use App\Helpers;
use App\Core\Database;
use App\Enum\EUserTable;
use App\Helpers\NavigationHelper as nav;
use App\Models\User;

/**
 * Class UsersController
 * @since 30-03-2017
 * @version 1.0
 * @author R Haan
 */
class UserController extends Controller
{

    private $_users;
    private $database;

    public $_user;

    /**
     * UserController constructor
     */
    public function __construct()
    {        
        $this->database = Database::getConnection();
        $this->_users = $this->database->queryResultsToArray("SELECT * FROM `people`");
    }

    /**
     * Gets the show.
     */
	public function getShow()
	{
        $this->getAccount();
    }

    /**
     * Gets the change.
     */
    public function getChange()
    {
        $this->getAccount();

        if (isset($_POST['btnChange'])) 
        {
            if ($_POST['newPassword'] == $_POST['checkPassword']) 
            {
                if (password_verify($_POST['oldPassword'], $this->_user['pass']))
                {
                    $passwords = [EUserTable::Password => password_hash($_POST['newPassword'], PASSWORD_BCRYPT)];
                    if($this->database->Update(nav::getSiteUsers(), $passwords))
                    {
                        $this->_out = "";
                        header("location: " . nav::getUserPage());
                    }
                    else
                    {
                        $this->_out = "Er is iets fout gegaan bij het wijzigen van het wachtwoord. Probeer opnieuw.";
                    }
                }
                else
                {
                    $this->_out = "Uw huidige wachtwoord is niet correct ingevuld";
                }
            }
            else
            {
                $this->_out = "Het nieuwe wachtwoord komt niet overeen met de herhaalde wachtwoord";
            }
        }
    }

    public function getUpdate()
    {
        $this->getAccount();

        if (isset($_POST['btnSave'])) 
        {
            $regexEMail = '/@.+\./';

            if (preg_match($regexEMail, $_POST['email'])) 
            {
                $this->data = 
                [
                    $_POST['email'],
                    $_POST['firstname'],
                    $_POST['tv'],
                    $_POST['lastname'],
                    $_POST['birthday'],
                    $_POST['address'],
                    $_POST['huisnr'],
                    $_POST['city'],
                    $_POST['postcode'],
                    $_POST['telefoonnummer'] 
                ];                 

                if ($this->database->updateAcc($this->data)) 
                {
                    $this->out = "";
                    header("location: "  . nav::getUserPage());
                } 
                else
                {
                    $this->out = "de velden gebruikersnaam, wachtwoord en email zijn verplichte velden";
                }             
            }   
            else
            {
                $this->out = "Uw gegeven Email is niet geldig.";
            }                      
        }
        else
        {
            // Do nothing, because the button isn't pressed.
        }
    }

    /**
     * Get the logged in account.
     * @return string
     */
    private function getAccount()
    {
        foreach ( $this->_users as $user )
        {
            if ($user['id'] == $_SESSION['id'] )
            { 
                $this->_user = $user;
                break;
            }
            else
            {
                // Do nothing, try next user.
            }
        }
    }

    /**
     * Gets the date. 
     */
    public function getDate( string $date )
    {
        return Helpers\DateHelper::getDateTimeValue( $date );
    }
}
?>