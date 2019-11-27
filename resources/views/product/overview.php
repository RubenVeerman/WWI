<?php

$products = [];
$empty = false;

if(isset($_GET["searchInput"]) && !empty($_GET["searchInput"]))
{
    $products = getSearchResult($_GET["searchInput"]);
}
else
{
    $products = selectProducts();
}
if(isset($_GET["category"])){
    $products = selectProductsCategory($_GET["category"]);
} else {
    $product = selectProducts();
}

if($products == NULL){
    $empty = true;
}


$categories = selectCategories();



?>

<div class="row">

    <div class="col-md-2">
        <ul class="list-group sticky-top">
        <?php

        foreach($categories as $category){
        ?>
            <a href="?page=product&action=overview&category=<?= $category["StockGroupID"] ?>"><li class="list-group-item list-group-item-action small mt-1 <?= setWhenActiveCategory($category["StockGroupID"]) ? "active" : "" ?>"><?=$category["StockGroupName"];?></li></a>
        <?php
        }
        ?>
        </ul>
    </div>
    <div class="col-md-10">
    <div class="container">
    <div class="col-md-auto">
<?php
if($empty){
    echo "<h1>This category is empty</h1>";
}
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
                <div class="card border-primary bg-light shadow" style="width: auto;">
                    <img class="card-img-top" style="height: 150px" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.mariescorner.com%2Fwp-content%2Fthemes%2Fmceighteen%2Fimg%2Fnopicture.png&f=1&nofb=1" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product["StockItemName"]?></h5>
                        <h2 class="card-title">€ <?= $product["RecommendedRetailPrice"]?></h2>
                        <a href="#" class="btn btn-success" style="width: 100%;">Voeg toe aan winkelwagen</a>
                    </div>
                </div>
            </a>
        </div>
<?php
}
?>
    </div>
</div>
</div>