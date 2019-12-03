<?php
/**
 * Created by PhpStorm.
 * User: rubje
 * Date: 02-Dec-19
 * Time: 15:10
 */
if(isset($_SESSION[IS_AUTHORIZED])){
    if($_SESSION[IS_AUTHORIZED]){
        echo '<div class="">
        <div class="col-lg">
            <ul class="nav nav-tabs">
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
                                    <td>'. $_SESSION["userName"] .'</td>
                                  </tr>
                                  <tr>
                                    <th>Full name</th>
                                    <td></td>
                                  </tr>
                                  <tr>
                                    <th>Preferred name</th>
                                    <td></td>
                                  </tr>
                              </table>
                            </p>
                        </div>
                    </div>
                    <!--/row-->
                </div>
                <div class="tab-pane" id="edit">
                    <form role="form">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Email</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="email" type="email" value="' . $_SESSION["userName"] . '">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Full name</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="fName" type="text" value="name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Preferred name</label>
                            <div class="col-lg-9">
                                <input class="form-control" name="pName" type="text" value="">
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
                                <input type="reset" class="btn btn-secondary" value="Cancel">
                                <input type="button" class="btn btn-primary" value="Save Changes">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>';
    }
}
else{
    echo 'You are not authorized to visit this page';
}