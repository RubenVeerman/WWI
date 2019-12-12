<div class="container">
<?php
$suppliers = suppliers();



if(isset($_SESSION['userName'])){
    $peopleInfo  = selectOnePeople($_SESSION['userName']);
    if($peopleInfo['IsSalesperson'] == 1 || $peopleInfo['IsSystemUser'] == 1 || $peopleInfo['IsEmployee'] == 1){
        $id = $_GET["id"];
        $product = selectProduct($id);
        $message = false;
        $stock = selectProductStock($id);

        if (isset($_GET["filename"])) {
            $fname = $_GET["filename"];
            $filename = "./public/images/";
            $filename .= $fname;
        }

        $arr = dbPhoto($product["StockItemID"]);


        if (isset($_POST["submit"])) {
            $stockitemname = $_POST["productname"];
            $supplierID = $_POST["supplierID"];
            $unitPackageID = 7;
            $outerPackageID = 7;
            $leadTimeDays = $_POST["leadTimeDays"];
            $quantityPerOuter = $_POST["quantityPerOuter"];
            $taxRate = $_POST["taxRate"];
            $unitPrice = $_POST["unitPrice"];
            $recommendedRetailPrice = $unitPrice * ($taxRate/100+1);
            $typicalWeightPerUnit = $_POST["typicalWeightPerUnit"];
            $marketingComments = $_POST["marketingcomments"];
            $searchDetails = $_POST["searchDetails"];
            $lastEditedBy = selectOnePeople($_SESSION['userName']);;
            $stock = $_POST["stock"];
            $product = selectProduct($id);

            $required = array('productname', 'supplierID', 'leadTimeDays', 'quantityPerOuter', 'taxRate', 'unitPrice','typicalWeightPerUnit','marketingcomments','searchDetails', 'stock');

// Loop over field names, make sure each one exists and is not empty
            $error = false;
            foreach($required as $field) {
                if (empty($_POST[$field])) {
                    $error = true;
                }
            }

            if ($error) {
                echo '<div class="alert alert-danger text-center"><strong>Failed!</strong> All fields are required.</div>';
            } else {
                updateProduct($stockitemname, $supplierID, $unitPackageID, $outerPackageID, $leadTimeDays,$quantityPerOuter,$taxRate,$unitPrice,$typicalWeightPerUnit,$marketingComments,$searchDetails, $lastEditedBy['PersonID'], $id, $recommendedRetailPrice);
                setStock($stock, $id, $lastEditedBy);
                $message = true;

            }
        } else {
            $stockitemname = "";
            $recprice = "";
            $marketingcomments = "";
        }
        if ($message == true) {
            echo '<div class="alert alert-success text-center"><strong>Succes!</strong> This product has been updated.</div>';
            $message = false;
        }
        if (isset($_GET["delete"])) {
            echo '<div class="alert alert-success text-center"><strong>Succes!</strong> The image has been deleted.</div>';
            deletePhoto($_GET["delete"]);

            unlink($filename);


            $_GET["delete"] = "";
        }
        if (isset($_GET["deleteproduct"])) {
            deleteProductStock($_GET["deleteproduct"]);
            deleteProduct($_GET["deleteproduct"]);
            header('Location: ' . "?page=product&action=overview&deleteproduct=success");
        }
        if (isset($_GET["upload"]) && $_GET["upload"] == "success") {
            echo '<div class="alert alert-success text-center"><strong>Succes!</strong> The image has been uploaded.</div>';
            $_GET["upload"] = "";
        }
        if (isset($_GET["upload"]) && $_GET["upload"] == "failed") {
            echo '<div class="alert alert-danger text-center"><strong>Failed!</strong> Uploading has been failed.</div>';
            $_GET["upload"] = "";
        }
        $stock = selectProductStock($id);

        ?>

        <a href="?page=product&action=overview""><button class="btn btn-success">Go Back</button></a>
        <a href="?page=manage&deleteproduct=<?= $id ?>">
            <button class="btn btn-danger">Delete</button>
        </a>



        <form class="form-group" method="post" action="?page=manage&id=<?= $id ?>">
            Product ID:
            <div class="form-control" readonly> <?= $product["StockItemID"] ?></div>
            Product Name:
            <input type="text" class="form-control" value="<?= $product["StockItemName"] ?>" name="productname">
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
            <input type="number" class="form-control" value="<?= $product["LeadTimeDays"] ?>" name="leadTimeDays">
            Quantity Per Outer:
            <input type="number" class="form-control" value="<?= $product["QuantityPerOuter"] ?>" name="quantityPerOuter">
            Unit Price:
            <input type="text" class="form-control" value="<?= $product["UnitPrice"] ?>" name="unitPrice">
            Tax Rate (in %):
            <input type="number" class="form-control" value="<?= $product["TaxRate"] ?>" name="taxRate">
            Typical Weight Per Unit:
            <input type="text" class="form-control" value="<?= $product["TypicalWeightPerUnit"] ?>" name="typicalWeightPerUnit">
            Marketing Comments:
            <input type="text" class="form-control" value="<?= $product["MarketingComments"] ?>" name="marketingcomments">
            Search Detail:
            <input type="text" class="form-control" value="<?= $product["SearchDetails"] ?>" name="searchDetails">
            In Stock:
            <input type="number" class="form-control" value="<?= $stock["LastStocktakeQuantity"]?>" name="stock">
            <br>
            <input type="submit" class="btn btn-primary" name="submit" value="Update">

        </form>


        <form action="./public/images/upload.php" method="post" enctype="multipart/form-data">
            <input type="number" name="id" value="<?= $product["StockItemID"] ?>" hidden>
            <label for="fileToUpload">Photo:</label>
            <input type="file" class="form-control-file" accept="image/*" name="fileToUpload" id="fileToUpload">
            <br>
            <input type="submit" class="btn btn-primary" name="submit" value="Upload Image">
        </form>


        <?php

        $arr = dbPhoto($product["StockItemID"]);

        if ($arr != null && $arr[0]["delete"] != false) {
            $images = count($arr);
            $i = 0;

            while ($i < $images) {
                echo "<a href='index.php?page=manage&id=" . $product["StockItemID"] . "&delete=" . $arr[$i]['PhotoID'] . "&filename=" . str_replace("./public/images/", "", $arr[$i]['Path']) . "'>Delete=></img>";
                echo "<img style='height: 150px; width: 150px;' src='" . $arr[$i]['Path'] . "'>";
                $i++;

            }
        }


    }
}

?>

</div>
