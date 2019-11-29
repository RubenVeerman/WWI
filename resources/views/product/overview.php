<?php

$products = [];
$empty = false;

const DEFAULT_LIMIT = 30;
const DEFAULT_PN = 1;


$pn = getValueFromArray("pageno", $_GET, DEFAULT_PN);
$limit = getValueFromArray("limit", $_GET, DEFAULT_LIMIT);

$start_from = ($pn-1) * $limit;

if(isset($_GET["searchInput"]) && !empty($_GET["searchInput"]))
{
    $products = getSearchResult($_GET["searchInput"]);
}
else
{
    $products = pagination($start_from, $limit);
}
if(isset($_GET["category"])){
    $total_products = countProductsOfCategory($_GET["category"]);
    $products = selectProductsCategory($_GET["category"], $start_from, $limit);
    $currentcategory = "&category=". $_GET["category"];
} else {
    $products = pagination($start_from, $limit);
    $currentcategory = "";
    $total_products = countProducts();
}

if($products == NULL){
    $empty = true;
}



$categories = selectCategories();

$pagelimit = "&limit=" . $limit;

echo getPaginationBar($total_products, $limit, $pn, $currentcategory, $pagelimit);
?>
<div class="row mb-5">

    <div class="col-md-2">
        <ul class="list-group sticky-top">
            <a href="?page=product&action=overview"><li class="list-group-item list-group-item-action small mt-1 <?= setWhenActive($categories[""], LVL_CAT);?>">All products</li></a>
        <?php

        foreach($categories as $category){
        ?>
            <a href="?page=product&action=overview&category=<?= $category["StockGroupID"] ?>"><li class="list-group-item list-group-item-action small mt-1 <?= setWhenActive($category["StockGroupID"], LVL_CAT) ?>"><?=$category["StockGroupName"];?></li></a>
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
    if($i % 4 == 0)
    {
        ?>
            </div>
        </div><br>
        <div class="container">
            <div class="row">
    <?php
    }

    $arr = dbPhoto($product["StockItemID"]);
//    echo "<pre>" . var_dump($arr) . "</pre>";
//    die();
    ?>

        <div class="col-sm-3">
            <a style="color: black" href="?page=product&action=show&id=<?= $product["StockItemID"] ?>">
                <div class="card border-primary bg-light shadow" style="width: auto;">
                    <img class="card-img-top img-fluid" style="height: 190px" src="<?=$arr[0]["Path"]?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product["StockItemName"]?></h5>
                        <h2 class="card-title">€ <?= $product["RecommendedRetailPrice"]?></h2>
                        <a href="#" class="btn btn-success" style="width: 100%;">Add to cart</a>
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

</div>

    <?= getPaginationBar($total_products, $limit, $pn, $currentcategory, $pagelimit); ?>