<div class="container">
<?php
if(isset($_SESSION['userName'])){
$peopleInfo  = selectOnePeople($_SESSION['userName']);
if($peopleInfo['IsSalesperson'] == 1 || $peopleInfo['IsSystemUser'] == 1 || $peopleInfo['IsEmployee'] == 1){
$suppliers = suppliers();
if(isset($_POST["submit"])){
$stockItemName = $_POST["productname"];
$supplierID = $_POST["supplierID"];
$colorID = 1;
$unitPackageID = 7;
$outerPackageID = 7;
$leadTimeDays = $_POST["leadTimeDays"];
$quantityPerOuter = $_POST["quantityPerOuter"];
$isChillerStock = 0;
$taxRate = $_POST["taxRate"];
$unitPrice = $_POST["unitPrice"];
$recommendedRetailPrice = $unitPrice * ($taxRate/100+1);
$typicalWeightPerUnit = $_POST["typicalWeightPerUnit"];
$marketingComments = $_POST["marketingcomments"];
$searchDetails = $_POST["searchDetails"];
$lastEditedBy = selectOnePeople($_SESSION['userName']);
$message = true;
$stock = $_POST["stock"];
$validFrom = "2016-05-31 23:00:00";
$validTo = "9999-12-31 23:59:59";
createProduct($stockItemName,$supplierID,$colorID,$unitPackageID, $outerPackageID, $leadTimeDays, $quantityPerOuter,$isChillerStock,$taxRate,$unitPrice,$typicalWeightPerUnit,$marketingComments,$searchDetails,$lastEditedBy['PersonID'],$validFrom, $validTo, $stock, $recommendedRetailPrice);
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


        if ($message == true) {
            echo '<div class="alert alert-success text-center"><strong>Succes!</strong> This product has been added.</div>';
            $message = false;
        }
        ?>

<form class="form-group" method="post" action="?page=manage&action=add">
    Product Name:
    <input type="text" class="form-control" placeholder="Product Name" name="productname">
    Supplier ID:
    <select class="form-control" name="supplierID">

        <?php
        foreach ($suppliers as $supp){
            print("<option value='" . $supp["SupplierID"] . "'>");
            print($supp["SupplierName"]);
            print("</option>");
        }
        ?>
    </select>
    Lead Time Days:
    <input type="number" class="form-control" placeholder="Lead Time Days" name="leadTimeDays">
    Quantity Per Outer:
    <input type="number" class="form-control" placeholder="Quantity Per Outer" name="quantityPerOuter">
    Unit Price:
    <input type="text" class="form-control" placeholder="Unit Price" name="unitPrice">
    Tax Rate (in %):
    <input type="number" class="form-control" placeholder="Tax Rate" name="taxRate">
    Typical Weight Per Unit:
    <input type="text" class="form-control" placeholder="Typical Weight Per Unit" name="typicalWeightPerUnit">
    Marketing Comments:
    <input type="text" class="form-control" placeholder="Marketing Comments" name="marketingcomments">
    Search Detail:
    <input type="text" class="form-control" placeholder="Search Detail" name="searchDetails">
    In Stock:
    <input type="number" class="form-control" placeholder="In Stock" name="stock">
    <br>
    <input type="submit" class="btn btn-primary" name="submit" value="Submit">

        </form>
        <?php
    }
}
        ?>

</div>