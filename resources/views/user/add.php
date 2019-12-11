<div class="container">
<?php
if(isset($_SESSION['userName'])){
    $peopleInfo  = selectOnePeople($_SESSION['userName']);
    if($peopleInfo['IsSalesperson'] == 1 || $peopleInfo['IsSystemUser'] == 1 || $peopleInfo['IsEmployee'] == 1){
        if (isset($_POST["addPeople"])) {
        }
        ?>
        <form class="form-group" method="post">
            First name:
            <input type="text" class="form-control"  name="fName" required>
            Last name:
            <input type="text" class="form-control" name="lName" required>
            Email address:
            <input type="email" class="form-control" name="email" required>
            Is external logon provider:
            <select name="externalLogonProvider" class="custom-select" id="inputGroupSelect01">
                <option value="false" selected>False</option>
                <option value="True">True</option>
            </select>
            Password:
            <input type="password" class="form-control" name="pass1" required>
            Repeat password:
            <input type="password" class="form-control" name="pass2" required>
            Is system user:
            <select name="systemUser" class="custom-select">
                <option value="false" selected>False</option>
                <option value="True">True</option>
            </select>
            Is employee:
            <select name="employee" class="custom-select">
                <option value="false" selected>False</option>
                <option value="True">True</option>
            </select>
            Is salesperson:
            <select name="salesperson" class="custom-select" >
                <option value="false" selected>False</option>
                <option value="True">True</option>
            </select>
            Phone number:
            <input type="number" class="form-control" name="phoneNumber">
            Fax number:
            <input type="number" class="form-control" name="faxNumber">
            <br>
            <input type="submit" class="btn btn-primary" name="addPeople" value="Submit">

        </form>
        <?php
    }
}
?>
</div>