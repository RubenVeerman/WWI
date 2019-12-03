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
?>

    <form class="form-group" method="post" action="?page=manage&id=<?= $id ?>">
        Product ID:
        <input type="text" class="form-control" value="<?= $product["StockItemID"]?>" name="id" readonly>
        Product Name:
        <input type="text" class="form-control" value="<?= $product["StockItemName"]?>" name="productname">
        Retail Price:
        <input type="text" class="form-control" value="<?= $product["RecommendedRetailPrice"]?>" name="retailprice">
        Marketing Comments:
        <input type="text" class="form-control" value="<?= $product["MarketingComments"]?>"name="marketingcomments">
        Custom fields:
            <label for="exampleFormControlFile1">Photo</label>
            <input type="file" class="form-control-file" id="photo">


    <input type="submit" class="btn btn-primary" name="submit">

    </form>
