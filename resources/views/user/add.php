<div class="container">
<?php
if(isset($_SESSION['userName'])){
    $peopleInfo  = selectOnePeople($_SESSION['userName']);
    function classCheck($inputName){
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
    if($peopleInfo['IsSalesperson'] == 1 || $peopleInfo['IsSystemUser'] == 1 || $peopleInfo['IsEmployee'] == 1){
        if (isset($_POST["addPeople"])) {
            validateRegistration('admin');
            }
        if(isset($_GET['add']) && $_GET['add'] == 'failed') {
            echo '<div class="alert alert-warning text-center">
                <strong>Failed!</strong> Some fields didnt have correct values.
              </div>';
        }
        if(isset($_GET['add']) && $_GET['add'] == 'fail') {
            echo '<div class="alert alert-warning text-center">
                <strong>Failed!</strong> There is already an account with this e-mail.
              </div>';
        }
        if(isset($_GET['add']) && $_GET['add'] == 'success') {
            echo '<div class="alert alert-success text-center">
                <strong>Success!</strong> account has been created successfully.
              </div>';
        }
        ?>
        <form class="form-group" method="post">
            First name:
            <input type="text" class="form-control <?= classCheck("fname")?>"  name="fname" value="<?= isset($_POST['fname']) ? $_POST['fname'] : '' ?>" required>
            Last name:
            <input type="text" class="form-control <?= classCheck("lname")?>" name="lname" value="<?= isset($_POST['lname']) ? $_POST['lname'] : '' ?>" required>
            Email address:
            <input type="email" class="form-control <?= classCheck("email")?>" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" required>
            Is external logon provider:
            <select name="externalLogonProvider" class="custom-select" id="inputGroupSelect01">
                <option value="0" selected>False</option>
                <option value="1">True</option>
            </select>
            Password:
            <input type="password" class="form-control <?= classCheck("pass1")?>" name="pass1" required>
            Repeat password:
            <input type="password" class="form-control <?= classCheck("pass2")?>" name="pass2" required>
            Is system user:
            <select name="systemUser" class="custom-select">
                <option value="0" selected>False</option>
                <option value="1">True</option>
            </select>
            Is employee:
            <select name="employee" class="custom-select">
                <option value="0" selected>False</option>
                <option value="1">True</option>
            </select>
            Is salesperson:
            <select name="salesperson" class="custom-select" >
                <option value="0" selected>False</option>
                <option value="1">True</option>
            </select>
            Phone number:
            <input type="number" class="form-control" name="phoneNumber" value="<?= isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '' ?>">
            Fax number:
            <input type="number" class="form-control" name="faxNumber" value="<?= isset($_POST['faxNumber']) ? $_POST['faxNumber'] : '' ?>">
            <br>
            <input type="submit" class="btn btn-primary" name="addPeople" value="Submit">

        </form>
        <?php
    }
}
?>
</div>