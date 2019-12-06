<?php
if(isset( $_SESSION['userName'])) {
    $peopleInfo = selectOnePeople($_SESSION['userName']);
    if ($peopleInfo['IsSalesperson'] == 1 || $peopleInfo['IsSystemUser'] == 1 || $peopleInfo['IsEmployee'] == 1) {
        if (isset($_GET['id'])) {
            echo 'yea';
            $peopleToDelete = getPeople($_GET['id']);
            if (archivePeople($peopleToDelete)) {
                echo 'yea';

                if (deletePeople($peopleToDelete)) {
                    echo 'yea';

                    header('Location: index.php?page=user&action=overview&delete=success');
                }

            }
        } else {
            echo 'No ID was given';
        }
    }
}
else {
    echo 'You are not authorized to view this page';
}