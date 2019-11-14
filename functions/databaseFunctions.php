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

