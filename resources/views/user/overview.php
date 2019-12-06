<div class="container row">
    <a href="?page=user&action=add" ><button type="button" class="btn btn-success" style="height: 40px">Add user</button></a>
    <div class="mx-auto">

<?php

/**
 * Created by PhpStorm.
 * User: rubje
 * Date: 04-Dec-19
 * Time: 11:12
 */
const DEFAULT_LIMIT = 100;
const DEFAULT_PN = 1;
if(isset($_SESSION['userName'])) {
    $peopleInfo = selectOnePeople($_SESSION['userName']);
    if ($peopleInfo['IsSalesperson'] == 1 || $peopleInfo['IsSystemUser'] == 1 || $peopleInfo['IsEmployee'] == 1) {
        $pn = getValueFromArray("pageno", $_GET, DEFAULT_PN);
        $limit = getValueFromArray("limit", $_GET, DEFAULT_LIMIT);
        $total_people = countPeople() - 1;
        $pagelimit = "&limit=" . $limit;
        $start_from = ($pn - 1) * $limit;
        echo getPaginationBar($total_people, $limit, $pn, 'users', $pagelimit);
        $peoples = selectPeople($start_from, $limit);
        if (isset($_GET['delete']) && $_GET['delete'] == 'success') {
            echo '<div class="alert alert-success text-center">
                <strong>Success!</strong> Account has been deleted.
              </div>';
        }
        if(isset($_GET['edit']) && $_GET['edit'] == 'success') {
            echo '<div class="alert alert-success text-center">
                <strong>Success!</strong> account has been updated successfully.
              </div>';
        }

        ?>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Full name</th>
                <th scope="col">Preferred name</th>
                <th scope="col">Email</th>
                <th scope="col">Is permitted to logon</th>
                <th scope="col">Is admin</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($peoples as $people) {
                if ($people['PersonID'] != $peopleInfo['PersonID']) {
                    echo '<tr>
            <th scope="row">' . $people['PersonID'] . '</th>
            <td>' . $people['FullName'] . '</td>
            <td>' . $people['PreferredName'] . '</td>
            <td>' . $people['EmailAddress'] . '</td>
            <td>';
                    if ($people['IsPermittedToLogon']) {
                        echo "True";
                    } else {
                        echo "False";
                    }
                    echo '</td>
                        <td>';
                    if ($people['IsSalesperson'] == 1 || $people['IsSystemUser'] == 1 || $people['IsEmployee'] == 1) {
                        echo "True";
                    } else {
                        echo "False";
                    }
                    echo ' </td>

            <td>
                <a href="index.php?page=user&action=show&id=' . $people['PersonID'] . '" class="btn btn-info">Show</a> 
                <a href="index.php?page=user&action=edit&id=' . $people['PersonID'] . '" class="btn btn-success">Edit</a> 
                <button class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter' . $people['PersonID'] . '">Delete</button>
            </td>
            <div class="modal fade" id="exampleModalCenter' . $people['PersonID'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <a href="index.php?page=user&action=delete&id=' . $people['PersonID'] . '" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </tr>
';
                }
            }
            ?>
            </tbody>
        </table>

        <?php
        echo getPaginationBar($total_people, $limit, $pn, 'users', $pagelimit);

    }
}
else{
    echo 'You are not authorized to view this page';
}?>

