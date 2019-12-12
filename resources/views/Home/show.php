<div class="container-fluid">
    <div id="specialDealsSlide" class="carousel slide">
        <div class="carousel-inner">
            <div class="container">

                <?php
                $products = getSpecialDeals();
                $first = true;
                foreach ($products as $product) {
                    $id = $product["StockItemID"];
                    $specialdeal = selectSpecialDealByStockItemID($id);
                    $stock = selectProductStock($id);
                    $images = dbPhoto($product["StockItemID"]);

                    $discount = 0;
                    if (!empty($specialdeal)) {
                        $discount = getDiscount($product["RecommendedRetailPrice"], $specialdeal);
                    }

                    $customFields = json_decode($product["CustomFields"]);
                    $tags = json_decode($product["Tags"]);
                    $countTags = count($tags);
                    $description = $countTags == 0 ? "none" : "";

                    for ($i = 0; $i < $countTags; $i++) {
                        $comma = $i < ($countTags - 1) ? "," : "";
                        $description .= $tags[$i] . $comma;
                    }
                    $stock = $product['QuantityOnHand'];
                    $outputStock = "";
                    $stockClass = "";
                    if ($stock == 0) {
                        $stockClass = 'danger';
                        $outputStock = 'Sold out!';
                    } else if ($stock < 100) {
                        $stockClass = 'warning';
                        $outputStock = 'Be quick! Just a few left';
                    } ?>
                    <div class="carousel-item <?= $first ? 'active' : '' ?>" data-slide-number="<?= $i; ?>">
                        <div class="card my-2">
                            <div class="card-header">
                                <h2><?= $product["StockItemName"] ?></h2>
                            </div>
                            <div class="card-body row">
                                <div class="col-sm p-2">
                                    <img class="d-block w-100" src="<?= $images[0]["Path"] ?>">
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
                                        <?php if (empty($specialdeal)) { ?>
                                            <h1> €<?= $product["RecommendedRetailPrice"]; ?></h1>
                                        <?php } else { ?>
                                            <h2 class="text-danger">
                                                <s>€<?= $product["RecommendedRetailPrice"]; ?></s>
                                            </h2>
                                            <h1 class="text-success">€<?= $discount; ?></h1>
                                        <?php } ?>
                                        <br>
                                        <form method="POST" class=" mb-0">
                                            <input type="hidden" name="amount" value="1">
                                            <input type="hidden" name="productID" value="<?=$product["StockItemID"];?>">
                                            <button type="submit" name="AddToCart" class="btn btn-success">Add to cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php 
                $first = false;} ?>

            </div>
        </div>
        <a class="carousel-control-prev" href="#specialDealsSlide" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#specialDealsSlide" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>