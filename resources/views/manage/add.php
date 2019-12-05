<?php

if(isset($_POST["submit"])){
$stockitemname = $_POST["productname"];
$supplierID = $_POST["supplierID"];
$unitPackageID = $_POST["unitPackageID"];
$outerPackageID = $_POST["outerPackageID"];
$lastEditedBy = selectOnePeople($_SESSION['userName']);;
$recprice = $_POST["retailprice"];
$marketingcomments = $_POST["marketingcomments"];
$message = true;
$stock = $_POST["stock"];
createProduct($stockitemname,$supplierID,$unitPackageID,$outerPackageID,$lastEditedBy['PersonID'],$recprice,$marketingcomments);
insertStock($stock, $lastEditedBy);
} else {
$stockitemname = "";
$supplierID = "";
$unitPackageID = "";
$outerPackageID = "";
$lastEditedBy = "";
$recprice = "";
$marketingcomments = "";
$stock = "";
$message = false;
}

if($message == true){
echo '<div class="alert alert-success text-center"><strong>Succes!</strong> This product has been added.</div>';
$message = false;
}
?>

<form class="form-group" method="post" action="?page=manage&action=add">
    Product Name:
    <input type="text" class="form-control" placeholder="Product Name" name="productname">
    Supplier ID:
    <input type="number" class="form-control" placeholder="Supplier ID" name="supplierID">
    UnitPackage ID:
    <input type="number" class="form-control" placeholder="UnitPackage ID" name="unitPackageID">
    OuterPackage ID:
    <input type="number" class="form-control" placeholder="OuterPackage ID" name="outerPackageID">
    Retail Price:
    <input type="text" class="form-control" placeholder="Retail Price" name="retailprice">
    Marketing Comments:
    <input type="text" class="form-control" placeholder="Marketing Comments" name="marketingcomments">
    In Stock:
    <input type="number" class="form-control" placeholder="In Stock" name="stock">
    <br>
    <input type="submit" class="btn btn-primary" name="submit" value="Submit">

</form>