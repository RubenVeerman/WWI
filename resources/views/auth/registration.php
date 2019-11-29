<?php
//$email = getValueFromArray("email", $_POST, );
    function classCheck($inputName)
    {
        $condition = true;
            if (isset($_POST[$inputName]))
            {
                if(!empty($_POST[$inputName])) {

                    switch ($inputName):
                        case "email":
                            $condition = strpos($_POST["email"], '@');
                            break;
                        case "pass1":
                        case "pass2":
                            $condition = $_POST['pass1'] == $_POST['pass2'] && strlen($_POST['pass1']) > 7;
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
?>
<form method="post">
    <div class="container">
        <div class="card">
            <div class="card-header">
                Registration form
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control <?= classCheck("email")?>" name="email" id="email" placeholder="Email address" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" >
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="email">First name:</label>
                            <input type="text" name="fname" class="form-control <?= classCheck("fname") ?>" placeholder="First name" value="<?= isset($_POST['fname']) ? $_POST['fname'] : '' ?>"">
                        </div>
                        <div class="col">
                            <label for="email">Last name:</label>
                            <input type="text" name="lname" class="form-control <?= classCheck("lname")?>" placeholder="Last name" value="<?= isset($_POST['lname']) ? $_POST['lname'] : '' ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="pass1">Password:</label>
                            <input type="password" name="pass1" class="form-control <?= classCheck("pass1") ?>" value="<?= isset($_POST['pass1']) ? $_POST['pass1'] : '' ?>"  placeholder="Password">
                        </div>
                        <div class="col">
                            <label for="pass2">Password:</label>
                            <input type="password" name="pass2" class="form-control <?= classCheck("pass2") ?>" value="<?= isset($_POST['pass2']) ? $_POST['pass2'] : '' ?>"  placeholder="Repeat Password">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" name="submit_registration" class="btn btn-primary">Create account</button>
            </div>
        </div>
    </div>
</form>