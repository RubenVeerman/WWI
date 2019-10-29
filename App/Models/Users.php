<?php
namespace App\Models;

use App\Core\Database;
use App\Helpers\NavigationHelper as nav;
use App\enum\EUserRights;
use App\Enum\EUserTable;

/**
 * The class Users is the object to get or set the user
 */
class Users 
{
	private $_users;

    /*
     * Users constructor
     */
	public function __construct()
	{
		$db = Database::getConnection();
		$this->_users = $db->queryResultsToArray("SELECT * FROM `" . nav::getSiteUsers() . "`");
	}

    /**
     * method to create connection with database table users
     */
    public function getUsers()
    {
        return $this->getStatus(EUserRights::User);
	}

    /**
     * method to create connection with database table admins
     */
	public function getAdmins()
	{
        return $this->getStatus(EUserRights::Admin);
	}

    /**
     * method to create connection with database table owners
     */
	public function getOwners()
	{
        return $this->getStatus(EUserRights::Owner);
	}

    /**
     * method to create connection with database table teachers
     */
	public function getTeachers()
	{
        return $this->getStatus(EUserRights::Teacher);
	}

	/**
     * method to check if account is user
     */
	public function isUser()
	{
        return $this->checkStatus(EUserRights::User);       
	}

    /**
     * method to check if account is admin
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->checkStatus(EUserRights::Admin); 
    }

    /**
     * method to check if account is owner
     */
	public function isOwner()
	{
        return $this->checkStatus(EUserRights::Owner);          
	}

    /**
     * method to check if account is teacher
     * @return boolean
     */
    public function isTeacher()
    {

        return $this->checkStatus(EUserRights::Teacher);   
    }

    /**
     * method to check if logged in with existing account
     * @return boolean
     */
    public function isValid()
    {
        try
        {
            foreach ($this->_users as $user) 
            {
                //
                // If the logged in account is exists
                //
                if ( isset($_SESSION) && $_SESSION[EUserTable::ID] == $user[EUserTable::ID])
                {
                    return true;
                }
            }

            return false;
          
        }
        catch (\Exception $ex) 
        {
            $this->HandleUnexpectedException($ex);
        } 
    }

    /**
     * method to sort array on specific item of the array.
     * @return array with sorted users.
     */
    public function sortAccounts($array, $position)
	{
        try
        {
    		$account = [];

    		foreach ($array as $key => $row)
    		{
    		    $account[$key] = $row[$position];
    		}

    		array_multisort($account, SORT_STRING, $array);

    		return $array;
          
        }
        catch (\Exception $ex) 
        {
            $this->HandleUnexpectedException($ex);
        } 
	}

    private function checkStatus($status)
    {
        try
        {
            foreach ($this->_users as $user) 
            {
                //
                // If the logged in account has the status
                //
                if (isset($_SESSION) && $_SESSION[EUserTable::ID] == $user[EUserTable::ID] && $user[EUserTable::Rights] == $status ) 
                {
                    return true;
                }
            } 

            return false;
          
        }
        catch (\Exception $ex) 
        {
            $this->HandleUnexpectedException($ex);
        } 
    }

    /**
     * Get Users based on there status
     */
    private function getStatus($status)
    {       
        try 
        {
            $contacts = [];

            foreach($this->_users as $user)
            {
                //
                // If the user has the right status
                //
                if ($user[EUserTable::Rights]  == $status) 
                {
                    //
                    // Push the user in the array
                    //
                    array_push($contacts, $user);   
                }
                else
                {
                    // Do nothing, keep searching.
                }
            }

            return $contacts;
          
        }
        catch (\Exception $ex) 
        {
            $this->HandleUnexpectedException($ex);
        } 
    }    

	/**
	 * Handles the unexpected exception.
	 */
	private function HandleUnexpectedException(\Exception $ex)
	{
		Utility::HandleUnexpectedException($ex, false);
	}
}