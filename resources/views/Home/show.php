<?php
    $products = getSpecialDeals();
?>


<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">

    <div class="carousel-inner">
        <?php
        $first = true;
        foreach ($products as $product)
        {
            $active = $first ? 'active' : '';
            $first = false;
        ?>
        <div class="carousel-item <?= $active ?>">
            <div class="row">
                <div class="col-sm">
                    <h2><?= $product["DealDescription"] ?></h2>
                    <?php if($product["Photo"] != NULL) {
                        echo $product["Photo"];
                    } else{
                        ?> <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.mariescorner.com%2Fwp-content%2Fthemes%2Fmceighteen%2Fimg%2Fnopicture.png&f=1&nofb=1"> <?php
                    }
                    ?>
                </div>
                <div class="col-sm">
                    <h4> <?= $product["StockItemName"]?></h4>

                    <p> <?= $product["MarketingComments"]?></p>
                    <br>
                    <h4>Nog <?= $product["LastStocktakeQuantity"]?> stuks beschikbaar</h4>
                    <br>
                    <h1> €<?= $product["RecommendedRetailPrice"]?></h1>
                    <br>
                    <button type="button" class="btn btn-success">Voeg toe aan winkelwagen</button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>