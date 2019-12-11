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
            foreach ($_SESSION["Cart"] as $cartProduct) {
                $product = selectProduct($cartProduct["id"]);
                $specialdeal = selectSpecialDealByStockItemID($cartProduct["id"]);
                $images = dbPhoto($cartProduct["id"]);
                $discount = $product["RecommendedRetailPrice"];
                if (!empty($specialdeal)) {
                    $discount = getDiscount($product["RecommendedRetailPrice"], $specialdeal);
                }


                $discountAmount = $discount * $cartProduct["amount"];
                $total += $discountAmount;
                ?>
                <div class="row border-bottom my-2 py-1">
                    <div class="col-md-3"><img class="img-thumbnail" src="<?= $images[0]["Path"] ?>" /></div>
                    <div class="col-md-3">
                        <b><?= $product["MarketingComments"] ?></b>
                        <div>Amount of product: </div>
                        <form method="POST">
                            <div class="input-group mb-3" style="width: 150px;">
                                <input type="hidden" name='productID' value="<?= $cartProduct["id"] ?>">
                                <input type="number" name="amount" class="form-control" value="<?= $cartProduct["amount"] ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" name="AddToCart" type="submit">update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3">
                        <h1 class="float-right">€<?= $discountAmount; ?></h1>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <h1>Total:</h1>
            <div class="form-group mb-1">
                <h1>€<?= $total ?></h1>
                <button class="btn btn-success form-control">pay</button>
                <div>
                </div>
            </div>
        </div>