<div class="container">
<?php
/**
 * Created by PhpStorm.
 * User: rubje
 * Date: 04-Dec-19
 * Time: 11:55
 */
if(isset( $_SESSION['userName'])) {
    $peopleInfo = selectOnePeople($_SESSION['userName']);
    if ($peopleInfo['IsSalesperson'] == 1 || $peopleInfo['IsSystemUser'] == 1 || $peopleInfo['IsEmployee'] == 1) {
        $peopleToShow = getPeople($_GET['id']);

        echo '<h5 class="card-title">User info</h5>
        <table class="table">';
        foreach ($peopleToShow as $key => $data){
            if($key != 'HashedPassword') {
                if ($key == 'IsPermittedToLogon' || $key == 'IsExternalLogonProvider' || $key == 'IsSystemUser' || $key == 'IsEmployee' || $key == 'IsSalesperson') {
                    if ($data == 1) {
                        $data = 'yes';
                    } else {
                        $data = 'no';
                    }
                }
                    echo '<tr><th>' . $key . '</th><td> ' . $data . '</td></tr>';

            }
        }
        echo '</table>
                <a href="index.php?page=user&action=edit&id=' . $peopleToShow['PersonID'] . '" class="btn btn-success">Edit</a> ';
    }
}
?>
</div>
