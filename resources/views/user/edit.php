<?php
    $return = '';
    /**
     * Created by PhpStorm.
     * User: rubje
     * Date: 04-Dec-19
     * Time: 11:55
     */
    if(isset( $_SESSION['userName'])) {

        $peopleInfo = selectOnePeople($_SESSION['userName']);
        if ($peopleInfo['PersonID'] != $_GET['id']) {
            function classCheck($inputName)
            {
                $condition = true;
                if (isset($_POST[$inputName])) {
                    if (!empty($_POST[$inputName])) {

                        switch ($inputName):
                            case "email":
                                $condition = strpos($_POST["email"], '@');
                                break;
                            case "pass1":
                            case "pass2":
                                $condition = $_POST['pass1'] == $_POST['pass2'] && strlen($_POST['pass1']) > 7;
                                break;
                            case "FullName":
                            case "PreferredName":
                            case "SearchName":
                                $condition = strlen($_POST[$inputName]) > 0;
                                break;
                            default:
                                break;
                        endswitch;

                        return $condition ? 'is-valid' : 'is-invalid';
                    } else {
                        return 'is-invalid';
                    }
                }
            }

            if (isset($_POST['editPeople'])) {
                if (checkEmailIfExists($_POST['LogonName'], $_GET['id'])) {
                    header("location: index.php?page=user&action=edit&id=" . $_GET['id'] . "&edit=failed");
                } else {
                    editPeopleAccount($_GET['id'], $peopleInfo['PersonID']);
                }
            }

            $return = "<div class=\"container\">";
            if (isset($_GET['edit']) && $_GET['edit'] == 'failed') {
                $return .= '
            <div class="alert alert-danger text-center">
                    <strong>Failed!</strong> There is already an account with this email.
                  </div>';
            }

            if ($peopleInfo['IsSalesperson'] == 1 || $peopleInfo['IsSystemUser'] == 1 || $peopleInfo['IsEmployee'] == 1) {
                $peopleToShow = getPeople($_GET['id']);

                $return .= '<h5 class="card-title">Edit user</h5>
        <form method="post">
            <table class="table">';
                foreach ($peopleToShow as $key => $data) {
                    if ($key != 'HashedPassword' && $key != 'EmailAddress' && $key != 'ValidFrom' && $key != 'ValidTo' && $key != 'LastEditedBy' && $key != 'Photo' && $key != 'CustomFields' && $key != 'UserPreferences' && $key != 'OtherLanguages') {
                        if ($key == 'IsPermittedToLogon' || $key == 'IsExternalLogonProvider' || $key == 'IsSystemUser' || $key == 'IsEmployee' || $key == 'IsSalesperson') {
                            if ($data == 1) {
                                $dataT = 'yes';
                                $oppositeT = 'no';
                                $data = '1';
                                $opposite = '0';
                            } else {
                                $dataT = 'no';
                                $oppositeT = 'yes';
                                $data = '0';
                                $opposite = '1';

                            }
                            $return .= '<tr><th>' . $key . '</th><td>
                              <select name="' . $key . '" class="custom-select" id="inputGroupSelect01">
                                <option value="' . $data . '" selected> ' . $dataT . '</option>
                                <option value="' . $opposite . '"> ' . $oppositeT . '</option>
                              </select>
                            </td></tr>
                            ';
                        } else {
                            if ($key == 'PersonID') {
                                $return .= '<tr><th>' . $key . '</th><td> <h6> <span class="badge badge-secondary">' . $data . '</span></h6></td></tr>';
                            } else {
                                $return .= '<tr><th>' . $key . '</th><td> <input class="form-control ' . classCheck($key) . '" type="text" name="' . $key . '" value="' . $data . '"></td></tr>';
                            }
                        }
                    }
                }
                $return .= '<tr ><td colspan="2"><input class="float-right btn btn-success" type="submit" name="editPeople" value="Edit user"></td></tr></table></form>';
            }
        }
        else{
            $return .='<div class="alert alert-warning text-center">
                <strong>Failed!</strong> You cant edit your own account here.
              </div>';

        }
    }

    echo $return . '</div>';

?>


