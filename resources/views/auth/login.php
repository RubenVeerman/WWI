<form method="post">
    <div class="container">
        <?php
        if(isset($_GET['registration']) && $_GET['registration'] == 'success') {
            echo '<div class="alert alert-success text-center">
                <strong>Success!</strong> Your account has been created successfully.
              </div>';
        }
        if(isset($_GET['login']) && $_GET['login'] == 'failed') {
            echo '<div class="alert alert-warning text-center">
                <strong>Failed!</strong> Wrong email or password.
              </div>';
        }
        ?>


        <div class="card">
            <div class="card-header">
                Enter credentials
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control" name="userName" id="email" placeholder="Email address" value="<?= isset($_POST['userName']) ? $_POST['userName'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" name="password" class="form-control" id="pwd" placeholder="Password" required>
                </div>
                <!-- <div class="form-group form-check">
                    <label class="form-check-label">
                    <input class="form-check-input" type="checkbox"> Remember me
                    </label>
                </div> -->                
            </div>
            <div class="card-footer">
                <button type="submit" name="submit_logon" class="btn btn-primary">Inloggen</button>
            </div>
        </div>    
    </div>
</form>