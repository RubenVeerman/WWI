<?php
/**
 * Created by PhpStorm.
 * User: rubje
 * Date: 02-Dec-19
 * Time: 15:10
 */
if(isset($_POST['updatePeople'])){
    updatePeople();
}
if(isset($_POST['updatePass'])){
    $passCorrect = checkCredentials($_SESSION['userName'], $_POST['oldPass']);
    if($passCorrect && strlen($_POST['pass1']) > 7 && $_POST['pass1'] == $_POST['pass2']){
        updatePass();
    }
    else{
        header("location: index.php?page=auth&action=profile&update=failed");
    }
}

$peopleInfo  = selectOnePeople($_SESSION['userName']);
if(isset($_SESSION[IS_AUTHORIZED])){
    if($_SESSION[IS_AUTHORIZED]){
        echo '<div class="">
        <div class="col-lg">';
        if(isset($_GET["update"]) && $_GET["update"] == "success") {
            echo '<div class="alert alert-success text-center">
                <strong>Success!</strong> Your account has been updated successfully.
                </div>';
        }
        if(isset($_GET["update"]) && $_GET["update"] == "failed") {
            echo '<div class="alert alert-warning text-center">
                <strong>Failed!</strong> Password didnt match.
              </div>';
        }

        echo '<ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="" data-target="#profile" data-toggle="tab" class="nav-link active">Profile</a>
                </li>
                <li class="nav-item">
                    <a href="" data-target="#edit" data-toggle="tab" class="nav-link">Edit</a>
                </li>
            </ul>
            <div class="tab-content">
              <p>
                <div class="tab-pane active" id="profile">
                    <h5 class="mb">User Profile</h5>
                    <div class="row">
                        <div class="col-md">
                            <p>
                            <table class="table table-striped">
                                  <tr>
                                    <th>Email</th>
                                    <td>'. $peopleInfo["LogonName"] .'</td>
                                  </tr>
                                  <tr>
                                    <th>Full name</th>
                                    <td>'. $peopleInfo["FullName"] .'</td>
                                  </tr>
                                  <tr>
                                    <th>Preferred name</th>
                                    <td>'. $peopleInfo["PreferredName"] .'</td>
                                  </tr>
                              </table>
                            </p>
                        </div>
                    </div>
                    <!--/row-->
                </div>
                <div class="tab-pane" id="edit">
                <div class="row">
                    <form class="col" method="post">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Email</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="email" type="email" value="'. $peopleInfo["LogonName"] .'">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Full name</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="fName" type="text" value="'. $peopleInfo["FullName"] .'">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Preferred name</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="pName" type="text" value="'. $peopleInfo["PreferredName"] .'">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                                <input type="submit" name="updatePeople" class="btn btn-primary" value="Save Changes">
                            </div>
                        </div>
                    </form>
                    <form class="col" method="post">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Password</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="oldPass" type="password" value="" placeholder="Old password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Password</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="pass1" type="password" value="" placeholder="New password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Confirm password</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="pass2" type="password" value="" placeholder="New password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                                <input type="submit" name="updatePass" class="btn btn-primary" value="Save New Password">
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        </div>';
    }
}
else{
    echo 'You are not authorized to visit this page';
}