<?php 

?>

<div class="container">
    <div class="card">
        <div class="card-header">
            Cart
        </div>
        <div class="card-body">
            <?php foreach($_SESSION["Cart"] as $cartProduct) { 
                $product = selectProduct($cartProduct["id"]);                
                $specialdeal = selectSpecialDealByStockItemID($cartProduct["id"]);
                $images = dbPhoto($cartProduct["id"]);
                $discount = 0;
                if(!empty($specialdeal)) {
                    $discount = getDiscount($product["RecommendedRetailPrice"], $specialdeal);
                }
            ?>
            <div class="row">
                <div class="col-md-3"><img src="<?= $images[0] ?>"/></div>
                <div class="col-md-3"><b><?= $product["MarketingComments"] ?></b></div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                <?php if(empty($specialdeal)) { ?>
                    <h1> €<?=$product["RecommendedRetailPrice"];?></h1>
                <?php } else { ?>
                    <h1>€<?=$discount;?></h1>
                <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="card-footer">
            Footer
        </div>
    </div>
</div>