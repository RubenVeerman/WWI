<?php
require_once "./functions/core.php";
require_once "./functions/databaseFunctions.php";

if(isset($_GET["searchInput"]))
{
    //stockitems
    //categories

    echo json_encode(selectProductsLike($_GET["searchInput"]));
}