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
function getNextID(){
    $connection = createConnection();
    $id_check_query = "SELECT MAX(PersonID) FROM people";
    $query_result = mysqli_query($connection, $id_check_query);
    $fetch = mysqli_fetch_array($query_result);
    $person_id = $fetch[0] + 1;
    return $person_id;
}
function createCustomerAccount(){
    $connection = createConnection();
    $personID = getNextID();
    $hashedPassword = password_hash($_POST["pass1"], PASSWORD_BCRYPT);
    $validToDate = '9999-12-31 23:59:59';
    $fullname = $_POST["fname"] . " " . $_POST["lname"];
    $searchName = $_POST["fname"] . " " . $fullname;
    $sql = "INSERT INTO people(PersonID, FullName, PreferredName, SearchName, IsPermittedToLogon, LogonName, IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesperson, EmailAddress, LastEditedBy, ValidFrom, ValidTo) 
            VALUES (?, ?, ?, ?, 1, ?, 0, ?, 0, 0, 0, ?, 1, NOW(), ?)";
    $statement = mysqli_prepare($connection, $sql);
    if ( !$statement ) {
        die('mysqli error: '.mysqli_error($connection));
    }
    mysqli_stmt_bind_param($statement, 'ssssssss', $personID, $fullname, $_POST["fname"], $searchName, $_POST["email"], $hashedPassword, $_POST["email"], $validToDate);
    mysqli_stmt_execute($statement);
    header("location: index.php?page=auth&action=login&registration=success");
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

function checkEmailIfExists($logonName)
{
    $connection = createConnection();
    $sql = "SELECT LogonName FROM people WHERE LogonName=?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 's', $logonName);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $arr = setResultToArray($result);

    return !empty($arr[0]);
}

function updateProduct($stockitemname,$supplierID,$unitPackageID,$outerPackageID,$lastEditedBy,$recprice,$marketingcomments,$id){
    $connection = createConnection();

    $stmt = $connection->prepare("UPDATE stockitems SET StockItemName=?, SupplierID=?,UnitPackageID=?, OuterPackageID=?,LastEditedBy=?, RecommendedRetailPrice=?, MarketingComments=? WHERE StockItemID=?");
    $stmt->bind_param('siiiiisi', $stockitemname,$supplierID,$unitPackageID,$outerPackageID,$lastEditedBy,$recprice,$marketingcomments, $id);
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
function createProduct($stockitemname, $supplierID, $unitPackageID, $outerPackageID, $lastEditedBy, $recprice, $marketingcomments){
    $connection = createConnection();
    $id = getNextStockID();
    $stmt = $connection->prepare("INSERT INTO stockitems (StockItemID, StockItemName, SupplierID, UnitPackageID, OuterPackageID, LastEditedBy, RecommendedRetailPrice, MarketingComments) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('isiiiiis', $id,$stockitemname, $supplierID, $unitPackageID, $outerPackageID, $lastEditedBy, $recprice, $marketingcomments);
    $stmt->execute();
    $stmt->close();

}


function setStock($stock, $id, $lasteditedby){
    $connection = createConnection();
    $stmt = $connection->prepare("UPDATE stockitemholdings SET LastStocktakeQuantity=?, LastEditedBy=? WHERE StockItemID=?");
    $stmt->bind_param('iii', $stock, $lasteditedby, $id);
    $stmt->execute();
    $stmt->close();

}


function insertStock($stock, $lastEditedBy){
    $connection = createConnection();
    $id = getNextStockID()-1;
    $stmt = $connection->prepare("INSERT INTO stockitemholdings (StockItemID, LastStocktakeQuantity, LastEditedBy) VALUES (?,?,?)");
    $stmt->bind_param('iii', $id, $stock, $lastEditedBy);
    $stmt->execute();
    $stmt->close();

}

function deleteProduct($id){
    $connection = createConnection();
    $stmt = $connection->prepare("DELETE FROM StockItems WHERE StockItemID=? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

function deleteProductStock($id){
    $connection = createConnection();
    $stmt = $connection->prepare("DELETE FROM stockitemholdings WHERE StockItemID=? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}





