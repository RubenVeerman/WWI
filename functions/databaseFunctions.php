<?php

function createConnection()
{
    //
    // Create an connection with the given information from that ini file.
    //
    $conn = mysqli_connect("localhost", "root", "", "wideworldimporters");

    //
    // If there seems to be an error,
    //
    if (mysqli_connect_errno())
    {
        //
        // Show the error and stop loading the website.
        //
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }

    if(!$conn)
    {
        throw new Exception("Something goes wrong mate!");
    }

    return $conn;
}

function closeConnection($connection)
{
    mysqli_close($connection);
}

// people


function checkCredentials($userName, $password) 
{
    $connection = createConnection();
    $sql = "SELECT HashedPassword FROM people WHERE LogonName=?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $arr = setResultToArray($result, true);
    if(!empty($arr)) {
       return password_verify($password, $arr['HashedPassword']);
    }
    return false;
}

// customers


// products

function selectProduct($id, $expectoneResult = true)
{

    $connection = createConnection();
    $sql = "SELECT * FROM stockitems WHERE StockItemID=?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'i', $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    $arr = setResultToArray($result, $expectoneResult);

    closeConnection($connection);
    return $arr;//mysqli_fetch_assoc($result);
}

function selectProductStock($id)
{
    $connection = createConnection();
    $sql = "SELECT * FROM stockitemholdings WHERE StockItemID=?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'i', $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $arr = setResultToArray($result, true);
    closeConnection($connection);
    // return mysqli_fetch_assoc($result);
    return $arr;
}

function selectProductsLike($searchInput, $column = "*")
{
    $connection = createConnection();
    $sql = "SELECT $column FROM stockitems WHERE SearchDetails LIKE ? OR StockItemID LIKE ? OR StockItemName LIKE ?";
    $statement = mysqli_prepare($connection, $sql);
    $like = "%{$searchInput}%";
    mysqli_stmt_bind_param($statement, 'sss', $like, $like, $like);
    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);

    $arr = setResultToArray($result);

    closeConnection($connection);



    return $arr;
}

function setResultToArray($result, $expectOneResult = false)
{
    $arr = [];

    while($row = mysqli_fetch_assoc($result)) {
        array_push($arr, $row);
    }

    if(count($arr) > 0 && $expectOneResult) {
        $arr = $arr[0];
    }

    return $arr;
}

function getSpecialDeals()
{
    $connection = createConnection();
    $sql = "SELECT * FROM specialdeals SD
            JOIN stockitems SI ON SI.StockItemID = SD.StockItemID
            JOIN stockitemholdings SH ON SH.StockItemID = SI.StockItemID";//" WHERE EndDate < " . date("Y/m/d");
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    closeConnection($connection);
    return $result;
}

function selectCategories()
{
    $connection = createConnection();
    $sql = "SELECT * FROM stockgroups ORDER BY StockGroupName";
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    closeConnection($connection);
    return $result;
}

function selectProductsCategory($id, $start_from, $limit)
{
    $connection = createConnection();
    $sql = "SELECT * FROM stockitems S JOIN stockitemstockgroups I ON S.StockItemID = I.StockItemID WHERE StockGroupID=? LIMIT $start_from, $limit";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'i', $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $arr = setResultToArray($result);
    closeConnection($connection);
    // return mysqli_fetch_assoc($result);
    return $arr;
}
function pagination($start_from, $limit)
{
    $connection = createConnection();
    $sql = "SELECT * FROM stockitems LIMIT $start_from, $limit";
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    closeConnection($connection);
    return $result;
}

function countProducts(){
    $connection = createConnection();
    $sql = "SELECT COUNT(*) FROM stockitems";
    $query = mysqli_query($connection, $sql);
    $row = mysqli_fetch_row($query);
    closeConnection($connection);
    return $row[0];
}
function countProductsOfCategory($categoryid){
    $connection = createConnection();
    $sql = "SELECT COUNT(*) FROM stockitems S JOIN stockitemstockgroups I ON S.StockItemID = I.StockItemID WHERE StockGroupID=?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'i', $categoryid);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_row($result);
    closeConnection($connection);
    return $row[0];
}
function countPeople()
{
    $connection = createConnection();
    $sql = "SELECT COUNT(*) FROM people";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_row($result);
    closeConnection($connection);
    return $row[0];
}
function getNextID(){
    $connection = createConnection();
    $id_check_query = "SELECT MAX(PersonID) FROM people";
    $query_result = mysqli_query($connection, $id_check_query);
    $fetch = mysqli_fetch_array($query_result);
    $person_id = $fetch[0] + 1;
    return $person_id;
}
function createCustomerAccount($type){
    $connection = createConnection();
    $personID = getNextID();
    $hashedPassword = password_hash($_POST["pass1"], PASSWORD_BCRYPT);
    $validToDate = '9999-12-31 23:59:59';
    $fullname = $_POST["fname"] . " " . $_POST["lname"];
    $searchName = $_POST["fname"] . " " . $fullname;
    if($type == 'person') {
        $sql = "INSERT INTO people(PersonID, FullName, PreferredName, SearchName, IsPermittedToLogon, LogonName, IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesperson, EmailAddress, LastEditedBy, ValidFrom, ValidTo) 
            VALUES (?, ?, ?, ?, 1, ?, 0, ?, 0, 0, 0, ?, 1, NOW(), ?)";
    }
    else{
        $sql = "INSERT INTO people(PersonID, FullName, PreferredName, SearchName, IsPermittedToLogon, LogonName, IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesperson, EmailAddress, LastEditedBy, ValidFrom, ValidTo, PhoneNumber, FaxNumber) 
            VALUES (?, ?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
    }
    $statement = mysqli_prepare($connection, $sql);
    if ( !$statement ) {
        die('mysqli error: '.mysqli_error($connection));
    }
    if($type == 'person') {
        mysqli_stmt_bind_param($statement, 'ssssssss', $personID, $fullname, $_POST["fname"], $searchName, $_POST["email"], $hashedPassword, $_POST["email"], $validToDate);
    }
    else{
        $peopleInfo  = selectOnePeople($_SESSION['userName']);
        mysqli_stmt_bind_param($statement, 'sssssssssssssss', $personID, $fullname, $_POST["fname"], $searchName, $_POST["email"], $_POST['externalLogonProvider'], $hashedPassword, $_POST["systemUser"], $_POST["employee"], $_POST["salesperson"], $_POST["email"], $peopleInfo['PersonID'], $validToDate, $_POST['phoneNumber'], $_POST['faxNumber']);
    }
    mysqli_stmt_execute($statement);
    if($type == 'person') {
        header("location: index.php?page=auth&action=login&registration=success");
    }
    else{
        header("location: index.php?page=user&action=add&add=success");
    }
}

function dbPhoto($id, $defaultPicture = true)
{
    $connection = createConnection();
    $sql = "SELECT * FROM PhotoID WHERE StockItemID=?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'i', $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
//    $row = mysqli_fetch_row($result);
    $arr = setResultToArray($result);
    closeConnection($connection);
    // return mysqli_fetch_assoc($result);

    if(empty($arr) && $defaultPicture)
    {
        $arr[0]["Path"] = "./public/images/noimage.png";
        $arr[0]["PhotoID"] ="";
        $arr[0]["delete"] = false;
    } else{
        $arr[0]["delete"] = true;
    }

    if(!$defaultPicture) {

        return null;
    }
    return $arr;
}

function selectSpecialDealByStockItemID($id)
{
    $connection = createConnection();
    $sql = "SELECT * FROM specialdeals WHERE StockItemID=?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'i', $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    $arr = setResultToArray($result, true);
    closeConnection($connection);

    return $arr;
}

function selectProductsByStockGroup($stockItemID) 
{
    $connection = createConnection();
    $sql = "SELECT DISTINCT S.* FROM stockitems S
            JOIN stockitemstockgroups IG ON IG.StockItemID=S.StockItemID
            WHERE IG.StockGroupID IN (SELECT StockGroupID FROM stockitemstockgroups WHERE StockItemID = ?)
            AND NOT S.StockItemID = ?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'ii', $stockItemID, $stockItemID);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $result = setResultToArray($result);
    closeConnection($connection);
    return $result;
}


function checkEmailIfExists($logonName, $id)
{
    $connection = createConnection();
    $sql = "SELECT PersonID FROM people WHERE LogonName=? AND PersonID!=?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'si', $logonName, $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $arr = setResultToArray($result);

    return !empty($arr[0]);
}

function updateProduct($stockitemname, $supplierID, $unitPackageID, $outerPackageID, $leadTimeDays,$quantityPerOuter,$taxRate,$unitPrice,$typicalWeightPerUnit,$marketingComments,$searchDetails, $lastEditedBy, $id, $recommendedRetailPrice){
    $connection = createConnection();

    $stmt = $connection->prepare("UPDATE stockitems SET StockItemName=?, SupplierID=?,UnitPackageID=?, OuterPackageID=?, LeadTimeDays=?, QuantityPerOuter=?,TaxRate=?,UnitPrice=?,TypicalWeightPerUnit=?,MarketingComments=?,SearchDetails=?,LastEditedBy=?, RecommendedRetailPrice=? WHERE StockItemID=?");
    $stmt->bind_param('siiiiidddssidi', $stockitemname, $supplierID, $unitPackageID, $outerPackageID, $leadTimeDays,$quantityPerOuter,$taxRate,$unitPrice,$typicalWeightPerUnit,$marketingComments,$searchDetails, $lastEditedBy, $recommendedRetailPrice, $id);
    $stmt->execute();
    $stmt->close();

}

function uploadPhoto($filename, $id){
    $connection = createConnection();

    $stmt = $connection->prepare("UPDATE photoid SET Path='./public/images/?' WHERE StockItemID=?");
    $stmt->bind_param('si', $filename, $id);
    $stmt->execute();
    $stmt->close();
}

function selectOnePeople($email){
    $connection = createConnection();
    $sql = "SELECT * FROM people WHERE LogonName='$email'";
    $result = mysqli_fetch_array(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    closeConnection($connection);
    return $result;
}
function getPeople($id){
    $connection = createConnection();
    $sql = "SELECT * FROM people WHERE PersonID=?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'i', $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $arr = setResultToArray($result, true);
    closeConnection($connection);
    return $arr;
}

function archivePeople($people){
    $connection = createConnection();
    foreach($people as $team)
        if(is_int($team)) {
            $data[] = '' . $team . '';
        }
        elseif(empty($team)){
            $data[] = 'NULL';
        }
        else{
            $data[] = '"' . $team . '"';
        }
    $data = implode("," , $data);
    $sql = "INSERT INTO people_archive VALUES (". $data.")";
    mysqli_query($connection, $sql);
    closeConnection($connection);
    return true;
}

function updatePeople(){
    $connection = createConnection();
    $fullname = $_POST['pName'] . " " . $_POST['fName'];
    $stmt = $connection->prepare("UPDATE people SET FullName=?, PreferredName=?, SearchName=?, LogonName=?, EmailAddress=? WHERE LogonName=?");
    $stmt->bind_param('ssssss', $_POST['fName'], $_POST['pName'], $fullname, $_POST['email'], $_POST['email'], $_POST['email']);
    $stmt->execute();
    $stmt->close();

    $_GET['update'] = 'success';
}

function updatePass(){
    $connection = createConnection();
    $hashedPassword = password_hash($_POST["pass1"], PASSWORD_BCRYPT);
    $stmt = $connection->prepare("UPDATE people SET HashedPassword=? WHERE LogonName=?");
    $stmt->bind_param('ss',$hashedPassword, $_SESSION['userName']);
    $stmt->execute();
    $stmt->close();

    $_GET['update'] = 'success';
}

function selectPeople($start_from, $limit){
    $connection = createConnection();
    $sql = "SELECT * FROM people ORDER BY PersonID DESC LIMIT $start_from, $limit";
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    closeConnection($connection);
    return $result;

}
function deletePeople($people)
{
    $PersonID = $people["PersonID"];
    $connection = createConnection();
    $sql = "DELETE FROM people WHERE PersonID = '$PersonID'";
    mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    if(mysqli_error($connection)){
        return false;
    }
    closeConnection($connection);
    return true;
}

function deletePhoto($id){
    $connection = createConnection();
    $stmt = $connection->prepare("DELETE FROM photoid WHERE PhotoID=? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

function getNextStockID(){
    $connection = createConnection();
    $id_check_query = "SELECT MAX(StockItemID) FROM stockitems";
    $query_result = mysqli_query($connection, $id_check_query);
    $fetch = mysqli_fetch_array($query_result);
    $person_id = $fetch[0] + 1;
    return $person_id;
}
function createProduct($stockItemName, $supplierID,$colorID, $unitPackageID, $outerPackageID,$leadTimeDays,$quantityPerOuter,$isChillerStock,$taxRate,$unitPrice,$weightPerUnit,$marketingComments,$searchDetails, $lastEditedBy,$validFrom, $validTo, $stock, $recommendedRetailPrice, $category){
    $connection = createConnection();
    $id = getNextStockID();
    $sql = "INSERT INTO stockitems (StockItemID, StockItemName, SupplierID,ColorID, UnitPackageID, OuterPackageID, LeadTimeDays, QuantityPerOuter,IsChillerStock,TaxRate,UnitPrice,TypicalWeightPerUnit,MarketingComments,SearchDetails, LastEditedBy, ValidFrom, ValidTo, RecommendedRetailPrice) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('isiiiiiiidddssissd', $id,$stockItemName, $supplierID,$colorID,$unitPackageID, $outerPackageID, $leadTimeDays,$quantityPerOuter,$isChillerStock,$taxRate,$unitPrice,$weightPerUnit,$marketingComments,$searchDetails, $lastEditedBy,$validFrom,$validTo, $recommendedRetailPrice);
    $stmt->execute();
    if(mysqli_error($connection)){
        echo mysqli_error($connection);
    }
    $stmt->close();
    insertCategory($id, $category, $lastEditedBy);
    insertStock($id, $stock, $lastEditedBy);


}

function getNextStockGroupID(){
    $connection = createConnection();
    $id_check_query = "SELECT MAX(StockItemStockGroupID) FROM stockitemstockgroups";
    $query_result = mysqli_query($connection, $id_check_query);
    $fetch = mysqli_fetch_array($query_result);
    $person_id = $fetch[0] + 1;
    return $person_id;
}

function insertCategory($id, $category, $lastEditedBy){
    $connection = createConnection();
    $groupID = getNextStockGroupID();
    $sql = "INSERT INTO stockitemstockgroups VALUES (?, ?, ?, ?, NOW())";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('ssss',$groupID, $id, $category, $lastEditedBy);
    $stmt->execute();
    if(mysqli_error($connection)){
        echo mysqli_error($connection);
    }
    $stmt->close();
}

function setStock($stock, $id, $lasteditedby){
    $connection = createConnection();
    $stmt = $connection->prepare("UPDATE stockitemholdings SET LastStocktakeQuantity=?, LastEditedBy=? WHERE StockItemID=?");
    $stmt->bind_param('iii', $stock, $lasteditedby, $id);
    $stmt->execute();
    $stmt->close();

}


function insertStock($id, $stock, $lastEditedBy){
    $connection = createConnection();
    $stmt = $connection->prepare("INSERT INTO stockitemholdings VALUES (?,?,'K-1',?,0.0,0,0,?,'2016-05-31 12:00:00')");
    $stmt->bind_param('iiii', $id,$stock, $stock, $lastEditedBy);
    $stmt->execute();
    if(mysqli_error($connection)){
        echo mysqli_error($connection);
    }
    $stmt->close();

}

function deleteProduct($id){
    $filenames = getFilename($id);
    deletePhoto($id);

    foreach($filenames as $filename) {
        unlink($filename["Path"]);
    }
    
    $connection = createConnection();
    $stmt = $connection->prepare("DELETE FROM StockItems WHERE StockItemID=? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

}

function getFilename($id){
    $connection = createConnection();
    $sql = "SELECT Path FROM photoid WHERE StockItemID=" . $id;
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    closeConnection($connection);
    return $result;
}

function deleteProductStock($id){
    $connection = createConnection();
    $stmt = $connection->prepare("DELETE FROM stockitemholdings WHERE StockItemID=? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}
function deleteProductCat($id){
    $connection = createConnection();
    $stmt = $connection->prepare("DELETE FROM stockitemstockgroups WHERE StockItemID=? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

function suppliers(){
        $connection = createConnection();
        $sql = "SELECT * FROM suppliers ORDER BY SupplierName";
        $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
        closeConnection($connection);
        return $result;
}

function category(){
    $connection = createConnection();
    $sql = "SELECT * FROM stockgroups";
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    closeConnection($connection);
    return $result;
}

function editPeopleAccount($id, $admin){
    if(!empty($_POST['FullName']) && !empty($_POST['PreferredName']) && !empty($_POST['SearchName']) && !empty($_POST['LogonName'])) {
        $connection = createConnection();
        $stmt = $connection->prepare("UPDATE people SET FullName=?, PreferredName=?, SearchName=?, IsPermittedToLogon=?, LogonName=?, EmailAddress=?, IsExternalLogonProvider=?, IsSystemUser=?, IsEmployee=?, IsSalesperson=?, PhoneNumber=?, FaxNumber=?, LastEditedBy=? WHERE PersonID = ?");
        $stmt->bind_param('sssissiiiiiiii', $_POST['FullName'], $_POST['PreferredName'], $_POST['SearchName'], $_POST['IsPermittedToLogon'], $_POST['LogonName'], $_POST['LogonName'], $_POST['IsExternalLogonProvider'], $_POST['IsSystemUser'], $_POST['IsEmployee'], $_POST['IsSalesperson'], $_POST['PhoneNumber'], $_POST['FaxNumber'], $admin, $id);
        $stmt->execute();
        if (mysqli_error($connection)) {
            echo mysqli_error($connection);
        }
        $stmt->close();

        header("location: index.php?page=user&action=overview&edit=success");
    }
    else{
        header("location: index.php?page=user&action=overview&edit=failed");

    }
}
function selectRandomStockItems()
{
    $connection = createConnection();
    $sql = "SELECT * FROM stockitems";
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
//    $result = array_rand($result, 4);
//    $sql = "SELECT * FROM stockitems WHERE StockItemID IN ($result[0], $result[1], $result[2], $result[3])";
//    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    closeConnection($connection);
    return $result;
}



