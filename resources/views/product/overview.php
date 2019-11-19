<?php

$products = [];

if(isset($_GET["searchInput"]) && !empty($_GET["searchInput"]))
{
    $products = getSearchResult($_GET["searchInput"]);
}
else
{
    $products = selectProducts();
}

?>
<div class="container">
    <div class="row">
<?php
for($i = 0; $i < count($products); $i++)
{
    $product = $products[$i];
    if($i % 3 == 0)
    {
        ?>
            </div>
        </div><br>
        <div class="container">
            <div class="row">
    <?php
    }
    ?>

        <div class="col-sm-4">
            <a style="color: black" href="?page=product&action=show&id=<?= $product["StockItemID"] ?>">
                <div class="card" style="width: auto;">
                    <img class="card-img-top" style="height: 150px" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.mariescorner.com%2Fwp-content%2Fthemes%2Fmceighteen%2Fimg%2Fnopicture.png&f=1&nofb=1" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product["StockItemName"]?></h5>
                        <h2 class="card-title">€ <?= $product["RecommendedRetailPrice"]?></h2>
                        <a href="#" class="btn btn-success">Voeg toe aan winkelwagen</a>
                    </div>
                </div>
            </a>
        </div>
<?php
}
?>
    </div>
</div>