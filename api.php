<?php
require_once "./functions/core.php";
require_once "./functions/databaseFunctions.php";

if(isset($_GET["query"]))
{
    //stockitems
    //categories
    $result = getSearchResult($_GET["query"]);


    $arr = [];
    foreach($result as $row)
    {
        array_push($arr, $row["StockItemName"]);
    }

    $json = json_encode($arr);

    print $json;
}
else {
    echo json_encode([]);
}