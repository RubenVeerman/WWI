<?php


$products = selectProducts();

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
    ?> <a href="?page=product&action=show&id=<?= $product["StockItemID"] ?>">
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading"><?= $product["StockItemName"] ?></div>
                <div class="panel-body"><img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%"
                     alt="Image"></div>
                <div class="panel-footer">€ <?= $product["RecommendedRetailPrice"] ?></div>
            </div>
        </div>
    </a>
<?php
}
?>
    </div>
</div>