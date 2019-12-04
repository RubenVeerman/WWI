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
$id = "/public/images/";
$id .= $_POST["id"];
$filename = $filename=$_FILES['fileToUpload']['name'];
function uploadPhoto($filename, $id){
    $connection = createConnection();

    $stmt = $connection->prepare("UPDATE photoid SET Path=? WHERE StockItemID=?");
    $stmt->bind_param('si', $filename, $id);
    $stmt->execute();
    $stmt->close();
}

$target_dir = "";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    echo "Sorry, only JPG, JPEG & PNG files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . "&upload=failed");
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        uploadPhoto($filename, $id);
        header('Location: ' . $_SERVER['HTTP_REFERER'] . "&upload=success");
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER'] . "&upload=failed");
    }
}


?>