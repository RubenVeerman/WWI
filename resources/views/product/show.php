<?php


$id = getValueFromArray("id", $_GET);
$product = selectProduct($id);
$product = $product[0];
$stock = selectProductStock($id);

?>

<div class="container">
    <div class="row">
        <div class="col-sm">
            <h2><?= $product["StockItemName"]?></h2>
            <?php if($product["Photo"] != NULL) {
                echo $product["Photo"];
            } else{
                ?> <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.mariescorner.com%2Fwp-content%2Fthemes%2Fmceighteen%2Fimg%2Fnopicture.png&f=1&nofb=1"> <?php
            }
            ?>
        </div>
        <div class="col-sm">
            <h4> <?= $product["MarketingComments"]?></h4>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi vel tempor lectus. Proin risus felis, pharetra sit amet egestas non, venenatis quis est. Suspendisse pharetra dictum tortor, ac auctor tellus egestas ut. Nulla scelerisque risus massa, in laoreet nisl egestas nec. Suspendisse a dictum ipsum. Sed auctor vehicula est, eu facilisis augue condimentum eget. Donec consectetur tristique volutpat. Nulla vestibulum tempus pellentesque. Donec elementum turpis dignissim consequat blandit.</p>
            <br>
            <h4>Nog <?= $stock["LastStocktakeQuantity"]?> stuks beschikbaar</h4>
            <br>
            <h1> €<?= $product["RecommendedRetailPrice"]?></h1>
            <br>
            <button type="button" class="btn btn-success">Voeg toe aan winkelwagen</button>
        </div>
    </div>
</div>
