<?php

$products = [];
$empty = false;

$limit = 30;


if (isset($_GET["pageno"])) {
    $pn = $_GET["pageno"];
}
else {
    $pn=1;
};

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

$pagelimit = "&limit=" . $limit


?>
<div class="row">
<div class="mx-auto">
    <div class="row">
    <nav aria-label="...">
        <ul class="pagination">

            <?php
            $total_pages = ceil($total_products / $limit);
            $pagLink = "";
            if($total_pages != 1) {
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $pn)
                        $pagLink .= "<li class='page-item active'><span class='page-link'>$i<span class='sr-only'>(current)</span></span></li>";
                    else
                        $pagLink .= "<li class='page-item'><a class='page-link' href='?page=product&action=overview&pageno=$i$currentcategory'>$i</a></li>";
                };
            }
            echo $pagLink;
            ?>
        </ul>
    </nav>
    <div class="dropdown">
        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            30
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="?page=product&action=overview&pageno=<?=$i?><?=$currentcategory?>&limit=15">15</a>
            <a class="dropdown-item" href="#">60</a>
            <a class="dropdown-item" href="#">90</a>
        </div>
    </div>
    </div>

</div>
</div>
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

    <div class="mx-auto mt-3">
    <nav aria-label="...">
        <ul class="pagination">

<?php
if($total_pages != 1) {
    $total_pages = ceil($total_products / $limit);
    $pagLink = "";
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $pn)
            $pagLink .= "<li class='page-item active'><span class='page-link'>$i<span class='sr-only'>(current)</span></span></li>";
        else
            $pagLink .= "<li class='page-item'><a class='page-link' href='?page=product&action=overview&pageno=$i$currentcategory'>$i</a></li>";
    };
}
echo $pagLink;
?>
        </ul>
    </nav>

</div>