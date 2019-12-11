<?php 

?>

<div class="container">
    <div class="card">
        <div class="card-header">
            Cart
        </div>
        <div class="card-body">
            
            <?php 
            $total = 0;
            foreach($_SESSION["Cart"] as $cartProduct) { 
                $product = selectProduct($cartProduct["id"]);                
                $specialdeal = selectSpecialDealByStockItemID($cartProduct["id"]);
                $images = dbPhoto($cartProduct["id"]);
                $discount = $product["RecommendedRetailPrice"];
                if(!empty($specialdeal)) {
                    $discount = getDiscount($product["RecommendedRetailPrice"], $specialdeal);
                }

                $total += $discount;
            ?>
            <div class="row border-bottom my-2">
                <div class="col-md-3"><img class="w-20" src="<?= $images[0]["Path"] ?>"/></div>
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
            Footer Total: <?= $total ?>
        </div>
    </div>
</div>