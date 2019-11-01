<?php
namespace App\Controllers;

use App\Core\Database;
use App\Enum;
use App\Helpers;
use App\Helpers\LoginHelper;
use App\Helpers\NavigationHelper as nav;
use App\Models;

/**
 * Class AdminController
 * @see Database
 * @since 30-03-2017
 * @version 1.5
 * @author R Haan0
 */
class AdminController
{
	private $database;
    
    public $modal = "";
    public $users =[];
    public $_isAdmin = false;
    public $message = "";
    public $_contacts = [];
    public $right;

    /**
     * Admin constructor.
     */
	public function __construct()
	{
        try
        {
            $this->database = Database::getConnection();

            //
            // If the user is logged in.
            //
            if (isset($_SESSION['username'])) 
            {
                $this->users = new Models\Users();

                //
                // If the user has admin rights
                //
                if ($this->users->isAdmin() || $this->users->isOwner() || $this->users->isTeacher()) 
                {
                    $this->_isAdmin = true;
                }
                else
                {
                    $this->_isAdmin = false;
                }
            }
        }
        catch(\Exception $ex)
        {
            $this->HandleUnexpectedException($ex, false);
        }
    }

    /**
     * method for the page /public/admin/show to handle the CRUD-requests
     */
	public function getShow()
    {
        try
        {
            //
            // If button deleteAcc is pressed
            //
            if (isset($_POST['deleteAcc']))
            {
                //
                // If the pressed radiobutton id value is not equal with the logged in account id
                //
                if ($_POST['rbtnAction'] != $_SESSION['id']) 
                {
                    //
                    // If the radiobutton is pressed and the delete action can be and is executed
                    //
                    if(isset($_POST['rbtnAction']) && $this->database->Delete(nav::getSiteUsers(), 'PersonID', $_POST["rbtnAction"]))
                    {
                        $this->message = "Account Verwijderd";
                    }
                    else
                    {
                        $this->message = "Het verwijderen van dit account is mislukt.";                    
                    }
                }
                else
                {
                    $this->message = "U kunt uw eigen account niet verwijderen.";
                }
            }
            //
            // If the promote button is pressed
            //
            elseif(isset($_POST['btnPromoteAcc']))
            {
                //
                // If the pressed radiobutton id value is not equal with the logged in account id
                //
                if ($_POST['rbtnAction'] != $_SESSION['id']) 
                {
                    //
                    // If the radiobutton is pressed and the promote action can be and is executed
                    //
                    if (isset($_POST['rbtnAction']) && $this->UpdateAcc(Enum\EUserRights::Admin))
                    {
                        $this->message = "Account gepomoveerd tot beheerder";
                    }
                    else
                    {
                        $this->message = "Het promoveren tot beheerder van dit account is mislukt.";                    
                    }
                }
                else
                {
                    $this->message = "U kunt uw eigen account niet promoveren tot beheerder.";                
                }
            }
            //
            // If the demote button is pressed
            //        
            elseif(isset($_POST['btnDemoteAcc']))
            {
                //
                // If the pressed radiobutton id value is not equal with the logged in account id
                //
                if ($_POST['rbtnAction'] != $_SESSION['id']) 
                {
                    //
                    // If the radiobutton is pressed and the demote action can be and is executed
                    //
                    if (isset($_POST['rbtnAction']) && $this->UpdateAcc(Enum\EUserRights::User))
                    {
                        $this->message = "Account gedegradeerd tot gebruiker";
                    }
                    else
                    {
                        $this->message = "Het degraderen tot gebruiker van dit account is mislukt.";                    
                    }
                }
                else
                {
                    $this->message = "U kunt uw eigen account niet degraderen tot gebruiker.";                
                }
            }
            //
            // If the promote teacher button is pressed
            //        
            elseif(isset($_POST['btnPromoteTeacher']))
            {
                //
                // If the pressed radiobutton id value is not equal with the logged in account id
                //
                if ($_POST['rbtnAction'] != $_SESSION['id']) 
                {
                    //
                    // If the radiobutton is pressed and the promote teacher action can be and is executed
                    //
                    if (isset($_POST['rbtnAction']) && $this->database->UpdateAcc(Enum\EUserRights::Teacher))
                    {
                        $this->message = "Account gepomoveerd tot leraar";
                    }
                    else
                    {
                        $this->message = "Het promoveren tot leraar van dit account is mislukt.";                    
                    }
                }
                else
                {
                    $this->message = "U kunt uw eigen account niet promoveren tot leraar.";
                }
            }
            
            $this->SortUsersOnLastname();
        }
        catch(\Exception $ex)
        {
            $this->HandleUnexpectedException($ex, true);
        }
    }

    /**
     * Updates the account.
     * @param right, the new right.
     * @return bool.
     */
    private function UpdateAcc($right)
    {

        $array =    [ Enum\EUserTable::Rights => $right
                    , Enum\EUserTable::ID => $_POST['rbtnAction']
                    ];

        if ($this->database->Update(nav::getSiteUsers(), $array))
        {
            return true;
        }

        return false;
    }

    /**
     * Sorts the Users, based on there rights, on their last name.
     */
    private function SortUsersOnLastname()
    {
        $users = new Models\Users();
        
        //
        // Sorting users on status and within the status sort on the user lastname
        //
        $sAdmins = $users->sortAccounts( $users->getAdmins(), "Lastname" );
        $sUsers = $users->sortAccounts( $users->getUsers(), "Lastname" );
        $sTeachers = $users->sortAccounts( $users->getTeachers(), "Lastname" );

        $this->users = array_merge($sAdmins, $sTeachers, $sUsers);
    }

    /**
     * Method to set de text of the modal dialog
     * @return string
     */
    private function getDialogText()
    {
        return "<table>
            <tbody>
            <tr><td colspan=\"2\">Weet u zeker dat u deze actie wilt uitvoeren?</td></tr>
            <tr>
                <td><input type=\"submit\" class=\"a-button a-delete\" name=\"deleteAcc\" value=\"Ga verder\"></td>
                <td><input type=\"button\" class=\"a-button a-cancel\" onclick=\"fCloseDialog()\" value=\"annuleren\"></td>
            </tr>
            </tbody>
        </table>";
    }

    /**
     * Method to get the modal dialog for deleting account
     * @return string
     */
    public function getDialog()
    {        
        $dialog = new Models\ModalDialog();
        $dialog->setHeaderText("Warning");
        $dialog->setDialogText($this->getDialogText());
        
        return $dialog;
    }

    /**
     * Method to get the authorization error
     * @return string
     */
    public function getError()
    {
        return LoginHelper::noAdminMessage();
    }    

	/**
	 * Handles the unexpected exception.
	 */
	private function HandleUnexpectedException(\Exception $ex, bool $showErrorToUser)
	{
		Helpers\Utility::HandleUnexpectedException($ex, $showErrorToUser);
	}
}
?>