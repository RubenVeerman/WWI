<form method="post">
    <div class="container">
        <div class="card">
            <div class="card-header">
                Voer gegevens in
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control" name="userName" id="email" placeholder="Email address" required>
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