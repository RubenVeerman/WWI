<?php

?>

<div class="container">
    <div class="card">
        <div class="card-header">
            Cart
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <h5>Product</h5>
                </div>
                <div class="col-md-2 px-0">
                    <h5>Price per product</h5>
                </div>
                <div class="col-md-2 px-0">
                    <h5>Total price products</h5>
                </div>
            </div>
            <?php
            $total = 0;
            $oldPriceTotal = 0;
            $totalDiscount = 0;
            foreach ($_SESSION["Cart"] as $cartProduct) {
                $product = selectProduct($cartProduct["id"]);
                $specialdeal = selectSpecialDealByStockItemID($cartProduct["id"]);
                $images = dbPhoto($cartProduct["id"]);

                $newPrice = $product["RecommendedRetailPrice"];
                if (!empty($specialdeal)) {
                    $newPrice = getDiscount($product["RecommendedRetailPrice"], $specialdeal);
                }

                $newPriceAmount = round_r($newPrice * $cartProduct["amount"]);
                $total = round_r($newPriceAmount + $total);

                $oldProductTotal = round_r($product["RecommendedRetailPrice"] * $cartProduct["amount"]);
                $oldPriceTotal = round_r($oldPriceTotal + $oldProductTotal);

                $discount = round_r(($product["RecommendedRetailPrice"] - $newPrice) * $cartProduct["amount"]);
                $totalDiscount = round_r($totalDiscount + $discount);
                ?>
                <div class="row border-bottom my-2 py-1 my-0">
                    <div class="col-md-3"><img class="img-thumbnail" src="<?= $images[0]["Path"] ?>" /></div>
                    <div class="col-md-3">
                        <b><?= $product["MarketingComments"] ?></b>
                        <div>Amount of product: </div>
                        <form method="POST">
                            <div class="input-group mb-3" style="width: 150px;">
                                <input type="hidden" name="override">
                                <input type="hidden" name='productID' value="<?= $cartProduct["id"] ?>">
                                <input type="number" name="amount" class="form-control" value="<?= $cartProduct["amount"] ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" name="AddToCart" type="submit">update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2">
                        <?php if (empty($specialdeal)) { ?>
                            <h1> €<?= $product["RecommendedRetailPrice"]; ?></h1>
                        <?php } else { ?>
                            <h2 class="text-danger">
                                <s>€<?= $product["RecommendedRetailPrice"]; ?></s>
                            </h2>
                            <h1 class="text-success">€<?= $newPrice; ?></h1>
                        <?php } ?>
                    </div>
                    <div class="col-md-2">
                        
                        <?php if (empty($specialdeal)) { ?>
                            <h1>€<?= $newPriceAmount; ?></h1>
                        <?php } else { ?>
                            <h2 class="text-danger">
                                <s>€<?= $oldPriceTotal; ?></s>
                            </h2>
                            <h1 class="text-success">€<?= $newPriceAmount; ?></h1>
                        <?php } ?>
                    </div>
                    <div class="col-md-2">
                    <div class="my-0">
                    <form method="POST" class="float-right">
                        <div class="input-group mb-3 float-right">
                            <input type="hidden" name="override">
                            <input type="hidden" name='productID' value="<?= $cartProduct["id"] ?>">
                            <input type="hidden" name="amount" value="0">
                            <button class="btn btn-danger float-right" name="AddToCart" type="submit">Delete</button>
                        </div>
                    </form>
                </div>
                    </div>
                </div>
            <?php } ?>
            <div class="d-flex justify-content-between">
                <div class="col-md-4">
                    <p class="border-bottom"><b>Total:</b></p>
                    <p><b>Total discount:</b></p>
                </div>
                <div class="col-md-4">
                    <div class="float-right">
                        <p class="border-bottom"><b>€<?=$oldPriceTotal?></b></p>
                        <p><b>€<?=$totalDiscount?></b></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <h1>Total to pay:</h1>
            <div class="form-group mb-1">
                <h1>€<?= $total ?></h1>
                <button class="btn btn-success form-control">pay</button>
                <div>
                </div>
            </div>
        </div>
    </div>  
</div>