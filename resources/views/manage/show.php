<?php
$id = $_GET["id"];
$product = selectProduct($id);
$message = false;
if(isset($_POST["submit"])){
    $stockitemname = $_POST["productname"];
    $recprice = $_POST["retailprice"];
    $marketingcomments = $_POST["marketingcomments"];
    $message = true;
    updateProduct($stockitemname,$recprice,$marketingcomments,$id);
    $product = selectProduct($id);

} else {
    $stockitemname = "";
    $recprice = "";
    $marketingcomments = "";
}
if($message == true){
    echo '<div class="alert alert-success text-center"><strong>Succes!</strong> This product has been updated.</div>';
    $message = false;
}

if(isset($_GET["upload"]) && $_GET["upload"] == "success"){
    echo '<div class="alert alert-success text-center"><strong>Succes!</strong> The image has been uploaded.</div>';
}
if(isset($_GET["upload"]) && $_GET["upload"] == "failed"){
    echo '<div class="alert alert-danger text-center"><strong>Failed!</strong> Uploading has been failed.</div>';
}
?>
<button class="btn btn-success" onclick="goBack()">Go Back</button>

<script>
    function goBack() {
        window.history.back();
    }
</script>

    <form class="form-group" method="post" action="?page=manage&id=<?= $id ?>">
        Product ID:
        <div class="form-control" readonly> <?= $product["StockItemID"]?></div>
        Product Name:
        <input type="text" class="form-control" value="<?= $product["StockItemName"]?>" name="productname">
        Retail Price:
        <input type="text" class="form-control" value="<?= $product["RecommendedRetailPrice"]?>" name="retailprice">
        Marketing Comments:
        <input type="text" class="form-control" value="<?= $product["MarketingComments"]?>"name="marketingcomments">
<br>
    <input type="submit" class="btn btn-primary" name="submit" value="Submit">

    </form>



<form class="form-group" action="./public/images/upload.php" method="post" enctype="multipart/form-data">
    <input type="number" name="id" value="<?=$product["StockItemID"]?>" hidden>
    <label for="exampleFormControlFile1">Photo</label>
    <input type="file" class="form-control-file" accept="image/*" name="fileToUpload" id="fileToUpload" >
    <input type="submit" class="btn btn-primary" name="submit" value="Upload Image">
</form>
