<?php
$id = getValueFromArray("id", $_GET, null);
$product = selectProduct($id);
$stock = selectProductStock($id);
$specialdeal = selectSpecialDealByStockItemID($product["StockItemID"]);
$discount = 0;
if(!empty($specialdeal)) {
    $discount = getDiscount($product["RecommendedRetailPrice"], $specialdeal);
}

$customFields = json_decode($product["CustomFields"]);
$tags = json_decode($product["Tags"]);

$description = "";
if(is_array($tags)) {   
    if(count($tags) == 0) {
        $description = "none";
    }

    for ($i = 0; $i < count($tags); $i++) {
        $comma = $i < (count($tags) - 1) ? "," : "";
        $description .= $tags[$i] . $comma;
    }
}

$outputStock = "";
$stockClass = "";
if ($stock["LastStocktakeQuantity"] == 0) {
    $stockClass = 'danger';
    $outputStock = 'Out of stock';
} else if ($stock["LastStocktakeQuantity"] < 100) {
    $stockClass = 'warning';
    $outputStock = 'Almost out of stock!';
}

$images = dbPhoto($product["StockItemID"]);

?>
<div class="container">
    <?= showProduct($product, true);?>
</div>

<?= getRelatedProducts(selectProductsByStockGroup($id)); ?>
