<?php

function CreateConnection() 
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

function SelecteerKlanten($connection)
{
    $sql = "SELECT nummer, naam, woonplaats FROM klant ORDER BY naam";
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    return $result;
}

function SluitVerbinding($connection) {
    mysqli_close($connection);
}

function VoegKlantToe($connection, $naam, $woonplaats) {
    $statement = mysqli_prepare($connection, "INSERT INTO klant (naam, woonplaats) VALUES(?,?)");
    mysqli_stmt_bind_param($statement, 'ss', $naam, $woonplaats);
    mysqli_stmt_execute($statement);
    return mysqli_stmt_affected_rows($statement) == 1;
}

function ZitNaamInQueryString($string){
    return isset($_GET[$string]);
}

function klantBestaat($naam)
{
    return mysqli_query(MaakVerbinding(), "select * from `klant` where naam=" .$naam);
}

function SelecteerKlant($connection, $nummer)
{
    $sql = "SELECT nummer, naam, woonplaats FROM klant WHERE nummer=" .$nummer;
    $result = mysqli_fetch_all(mysqli_query($connection, $sql), MYSQLI_ASSOC);
    return $result;
}

function BewerkKlant($connection, $nummer, $naam, $woonplaats) {
    $statement = mysqli_prepare($connection, "UPDATE klant SET naam =?, woonplaats =? WHERE nummer =?");
    mysqli_stmt_bind_param($statement, 'sss', $naam, $woonplaats, $nummer);
    mysqli_stmt_execute($statement);
    return mysqli_stmt_affected_rows($statement) == 1;
}


