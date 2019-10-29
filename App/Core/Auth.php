<?php
namespace App\Core;

use App\Enum\EUserTable;
use app\Helpers\LoginHelper;
use App\Helpers\NavigationHelper as nav;
use App\Models\ModalDialog;

/**
 * Class login
 * this class is used for loggin in to the website
 * @see db
 * @since 30-03-2017
 * @version 1.2
 * @author R Haan
 */
class Auth
{
	private $database;
	private $aUsers;
	private $form;
	private $message;
	private $user = '';
	private $pass = '';
    
    public $username = '';

    /**
     * Initializes a new instance of the Auth class.
     */
	public function __construct()
	{
		//
		// Get the database connection
		//
		$this->database = Database::getConnection();

		//
		// Get the users from query results from databes in an array
		//
		$this->aUsers = $this->database->queryResultsToArray("SELECT * FROM `" . nav::getSiteUsers() . "`");
	}

    /**
     * leading the logincheck
     * @return string
     */
	public function loginCheck()
	{
		//
        // If there is a session set an the session item 'login' is true.
        //
		if (LoginHelper::IsLoggedIn())
        {
        	//
        	// remove the login stuff.
        	//
        	$this->message = "";
        	$this->form = "";
        }  
        else
        {
        	//
        	// create the login stuff.
        	//
        	$this->message = 'Fill your username or password in please.';
        }

        //
        // If button the logout button is pressed.
        //
		if ( isset($_POST['logoutSubmit']) ) 
        {
        	//
        	// clear the session an redirect to the homepage.
        	//
            $_SESSION = array();
            header("location: " . nav::getHomePage());
        }
        else
        {
        	// Do nothing
        }

        //
        // If the login button is pressed and the username / email and password are given.
        //
		if ( isset($_POST['loginSubmit']) && !empty($_POST['Username']) && !empty($_POST['Password']) )
		{
			//
			// Catch the given username or given email and the given password.
			//
            $this->user = $_POST['Username'];
            $this->pass = $_POST['Password'];

            //
            // If there is given a valid username or a valid email.
            //
            if ( preg_match('/^[A-z\d_]{2,20}$/i', $this->user) || preg_match('/.@.+\./', $this->user) ) 
            {
            	//
            	// Check if user exists in database.
            	//
                $this->CheckUser();                
            }
            else
            {
            	//
            	// Tell the user the given username or email is not valid.
            	//
            	$this->message = "Given username or email is not valid.";
            }

            //
            // if the login session is set and true.
            //
            if (isset($_SESSION['login']) && $_SESSION['login'])
            {
            	//
            	// Create the username session and send the user to his profile.
            	//
            	$this->username = $_SESSION[EUserTable::UserName];
                header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
            }           	
		}
		//
		// If only the login button is pressed.
		//
		elseif ( isset($_POST['loginSubmit']) ) 
		{
			$this->message = "The input fields username or password is empty";
		}

		return $this->message;
	}

    /**
     * checking for the right account.
     * @return string
     */
	private function CheckUser()
	{
		foreach($this->aUsers as $user)
		{
			//
			// if the given account exists in the database.
			//
			if (
				( LoginHelper::UserCheck($this->user, $user[EUserTable::UserName]) 
   				|| LoginHelper::UserCheck($this->user, $user[EUserTable::EMail])
   				)   				
   				&& password_verify($this->pass, $user[EUserTable::Password])
			)
			{
				//
				//Create a user session an set the login session true.
				//
	            $_SESSION = $user;
				$_SESSION['login'] = true;						
				break;
			}
			else
			{
				//
				// do nothing.
				//
			}
		}
	}
}