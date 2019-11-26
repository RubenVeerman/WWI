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
    $sql = "SELECT * FROM people WHERE LogonName=? && HashedPassword=?";
    $stmt = mysqli_prepare($connection, $sql);
    //mysqli_stmt_bind_param($stmt, "ss", $userName, );
    mysqli_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// customers

function selectCustomers()
{
    $connection = createConnection();
    $sql = "SELECT nummer, naam, woonplaats FROM klant WHERE nummer=?";
    closeConnection($connection);
    return mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
}


function selectOneCustomer($conn, $id)
{
    $sql = "SELECT nummer, naam, woonplaats FROM klant WHERE nummer=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function voegKlantToe($connection, $naam, $woonplaats) 
{
    $statement = mysqli_prepare($connection, "INSERT INTO klant (naam, woonplaats) VALUES(?,?)");
    mysqli_stmt_bind_param($statement, 'ss', $naam, $woonplaats);
    mysqli_stmt_execute($statement);
    return mysqli_stmt_affected_rows($statement) == 1;
}

function customerExists($naam)
{
    return mysqli_query(createConnection(), "SELECT * FROM `klant` WHERE naam=" .$naam);
}

function bewerkKlant($connection, $nummer, $naam, $woonplaats) 
{
    $connection = createConnection();
    $sql = "UPDATE klant SET naam =?, woonplaats =? WHERE nummer =?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'sss', $naam, $woonplaats, $nummer);
    mysqli_stmt_execute($statement);
    
    return mysqli_stmt_affected_rows($statement) == 1;
}

// products


function selectProducts()
{
    $connection = createConnection();
    $sql = "SELECT * FROM stockitems ORDER BY StockItemName";
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    closeConnection($connection);
    return $result;
}

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
    $sql = "SELECT $column FROM stockitems WHERE SearchDetails LIKE ? OR StockItemID LIKE ?";
    $statement = mysqli_prepare($connection, $sql);
    $like = "%{$searchInput}%";
    mysqli_stmt_bind_param($statement, 'ss', $like, $like);
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

    if($expectOneResult) {
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

function selectProductsCategory($id)
{
    $connection = createConnection();
    $sql = "SELECT * FROM stockitems S JOIN stockitemstockgroups I ON S.StockItemID = I.StockItemID WHERE StockGroupID=?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, 'i', $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $arr = setResultToArray($result);
    closeConnection($connection);
    // return mysqli_fetch_assoc($result);
    return $arr;
}