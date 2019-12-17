<div class="container-fluid">
    <div id="specialDealsSlide" class="carousel slide">
        <div class="carousel-inner">
            <div class="container">

                <?php
                $products = getSpecialDeals();
                $first = true;
                foreach ($products as $product) {
                    echo showProduct($product, false, $first, true);
                    $first = false;
                } 
                ?>

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

<?= getRelatedProducts(selectRandomStockItems()); ?>