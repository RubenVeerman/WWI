<?php
    
    /**
     * Created by PhpStorm.
     * User: rubje
     * Date: 04-Dec-19
     * Time: 11:55
     */
    if(isset( $_SESSION['userName'])) {

        $peopleInfo = selectOnePeople($_SESSION['userName']);
        if(isset($_POST['editPeople'])){
            if(checkEmailIfExists($_POST['LogonName'], $_GET['id'])) {
                header("location: index.php?page=user&action=edit&id=". $_GET['id'] ."&edit=failed");
            } else {
                editPeopleAccount($_GET['id'], $peopleInfo['PersonID']);
            }
        }
        
        $return = "<div class=\"container\">";
        if(isset($_GET['edit']) && $_GET['edit'] == 'failed') {
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
                foreach ($peopleToShow as $key => $data){
                    if($key != 'HashedPassword' && $key != 'EmailAddress' &&  $key != 'ValidFrom' && $key != 'ValidTo' && $key != 'LastEditedBy' && $key != 'Photo' && $key != 'CustomFields' && $key != 'UserPreferences' && $key != 'OtherLanguages') {
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
                              <select name="'.$key.'" class="custom-select" id="inputGroupSelect01">
                                <option value="'.$data.'" selected> '.$dataT.'</option>
                                <option value="'.$opposite.'"> '.$oppositeT.'</option>
                              </select>
                            </td></tr>
                            ';
                        } else {
                            if($key == 'PersonID'){
                                $return .= '<tr><th>' . $key . '</th><td> <h6> <span class="badge badge-secondary">'. $data . '</span></h6></td></tr>';
                            }
                            else {
                                $return .= '<tr><th>' . $key . '</th><td> <input class="form-control" type="text" name="' . $key . '" value="' . $data . '" required></td></tr>';
                            }
                        }
                    }
                }
                $return .= '<tr ><td colspan="2"><input class="float-right btn btn-success" type="submit" name="editPeople" value="Edit user"></td></tr></table></form>';
        }
    }

    echo $return . '</div>';

?>


