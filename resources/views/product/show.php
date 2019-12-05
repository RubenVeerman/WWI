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
    <div class="row">
        <div class="col-sm p-2">
            <div id="productImageCarousel" class="carousel slide">
                <h2><?= $product["StockItemName"] ?></h2>
                <?php if (!empty($specialdeal)) { ?> 
                    <h3 class="text-success"><?= $specialdeal["DealDescription"]; ?></h3>
                <?php } ?>
                <div class="carousel-inner">
                    <?php
                    for ($i = 0; $i < count($images); $i++) { ?>
                        <div class="carousel-item <?= $i == 0 ? 'active' : '' ?>" data-slide-number="<?= $i; ?>">
                            <img class="d-block w-100" src="<?= $images[$i]["Path"] ?>">
                        </div>
                    <?php } ?>
                </div>
                <?php if(count($images) > 1) { ?> 
                    <a class="carousel-control-prev" href="#productImageCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#productImageCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>           
                    <ol class="carousel-indicators">
                        <?php for ($i = 0; $i < count($images); $i++) { ?>
                            <li data-target="#productImageCarousel" data-slide-to="<?= $i; ?>" class="<?= $i == 0 ? 'active' : ''; ?>">
                                <img class="d-block" src="<?= $images[$i]["Path"]; ?>">
                            </li>
                        <?php } ?>
                    </ol>
                <?php } ?>
            </div>
        </div>
        <div class="col-sm d-flex flex-column align-content-*-end">
            <div>
                <h4><?= $product["MarketingComments"] ?></h4>
                <p><b>Country of manufacture</b>: <?= $customFields->CountryOfManufacture ?? "" ?></p>
                <p><b>Specifications</b>: <?= $description; ?>
                </p>
                </br>
                <div class="text-<?= $stockClass; ?>"><?= $outputStock ?></div>
            </div>
            <div>
                <?php if(empty($specialdeal)) { ?>
                    <h1> €<?=$product["RecommendedRetailPrice"];?></h1>
                <?php } else { ?>
                    <h2 class="text-danger">
                        <strike>€<?=$product["RecommendedRetailPrice"];?></strike>
                    </h2>
                    <h1 class="text-success">€<?=$discount;?></h1>
                <?php } ?>
                </h1>
                <br>
                <button type="button" class="btn btn-success">Voeg toe aan winkelwagen</button>
            </div>
        </div>
    </div>
    <div class="row">

    </div>
</div>