<?php
if(isset($_SESSION[IS_AUTHORIZED])) {
    if ($_SESSION[IS_AUTHORIZED]) {
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
            $unitPackageID = $_POST["unitPackageID"];
            $outerPackageID = $_POST["outerPackageID"];
            $lastEditedBy = selectOnePeople($_SESSION['userName']);;
            $recprice = $_POST["retailprice"];
            $marketingcomments = $_POST["marketingcomments"];
            $stock = $_POST["stock"];
            $message = true;
            updateProduct($stockitemname, $supplierID, $unitPackageID, $outerPackageID, $lastEditedBy['PersonID'], $recprice, $marketingcomments, $id);
            setStock($stock, $id, $lastEditedBy);
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

        <button class="btn btn-success" onclick="goBack()">Go Back</button>
        <a href="?page=manage&deleteproduct=<?= $id ?>">
            <button class="btn btn-danger">Delete</button>
        </a>

        <script>
            function goBack() {
                window.history.back();
            }
        </script>

        <form class="form-group" method="post" action="?page=manage&id=<?= $id ?>">
            Product ID:
            <div class="form-control" readonly> <?= $product["StockItemID"] ?></div>
            Product Name:
            <input type="text" class="form-control" value="<?= $product["StockItemName"] ?>" name="productname">
            Supplier ID:
            <input type="text" class="form-control" value="<?= $product["SupplierID"] ?>" name="supplierID">
            UnitPackage ID:
            <input type="text" class="form-control" value="<?= $product["UnitPackageID"] ?>" name="unitPackageID">
            OuterPackage ID:
            <input type="text" class="form-control" value="<?= $product["OuterPackageID"] ?>" name="outerPackageID">
            Retail Price:
            <input type="text" class="form-control" value="<?= $product["RecommendedRetailPrice"] ?>"
                   name="retailprice">
            Marketing Comments:
            <input type="text" class="form-control" value="<?= $product["MarketingComments"] ?>"
                   name="marketingcomments">
            In stock:
            <input type="text" class="form-control" value="<?= $stock["LastStocktakeQuantity"] ?>" name="stock">

            <br>
            <input type="submit" class="btn btn-primary" name="submit" value="Submit">

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
                echo "<a href='index.php?page=manage&id=" . $product["StockItemID"] . "&delete=" . $arr[$i]['PhotoID'] . "&filename=" . str_replace("./public/images/", "", $arr[$i]['Path']) . "'>Delete</img>";
                echo "<img style='height: 150px; width: 150px;' src='" . $arr[$i]['Path'] . "'>";
                $i++;

            }
        }


    }
}

?>

