<?php
function getPaginationBar($total_products, $limit, $pn, $currentcategory, $pagelimit) {
    $total_pages = ceil($total_products / $limit);
    $pagLink = "";
    $dropdownLink = '';
    $items = 20;
    if($currentcategory != "users" || empty($currentcategory)) {
        if ($total_pages != 1) {
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $pn)
                    $pagLink .= "<li class='page-item active'><span class='page-link'>$i<span class='sr-only'>(current)</span></span></li>";
                else
                    $pagLink .= "<li class='page-item'><a class='page-link' href='?page=product&action=overview&pageno=$i$currentcategory$pagelimit'>$i</a></li>";
            };
        }
        $dropdownLink = '?page=product&action=overview&pageno=1' . $currentcategory;
    }
    if($currentcategory == "users") {
        if ($total_pages != 1) {
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $pn)
                    $pagLink .= "<li class='page-item active'><span class='page-link'>$i<span class='sr-only'>(current)</span></span></li>";
                else
                    $pagLink .= "<li class='page-item'><a class='page-link' href='?page=user&action=overview&pageno=$i$pagelimit'>$i</a></li>";
            };
        }
        $items = 50;
        $dropdownLink = '?page=user&action=overview&pageno=1';
    }


    if(isset($_GET["category"])){
        $cat = $_GET["category"];
        $catname = "category";
    } else {
        $cat = "";
        $catname = "";
    }


    $print = '
<div class="row">
    <div class="mx-auto">
        <div class="row">
            <nav aria-label="...">
                <ul class="pagination">'
                    . $pagLink. '
                </ul>
            </nav>
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    '. $limit . '
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                ';
    for($i = 1; $i < 5; $i++){
        $itemsPerPage = $items * $i;
        $print .= '
        <a class="dropdown-item" href="'. $dropdownLink . '&limit='. $itemsPerPage .'">'. $itemsPerPage .'</a>';
    }
        if($currentcategory != "users") {
            $print .= '   <form method="get">
                        <input type="hidden" name="page" value="product">
                        <input type="hidden" name="' . $catname . '" value="' . $cat . '">
                        <input type="hidden" name="action" value="overview">
                        <input type="hidden" name="pageno" value="1">
                      
                        
                        <div class="form-row m-1">
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" min="10" placeholder="Num..." name="limit">
                            </div>
                            <div class="col-6">
                                <input type="submit" value="Submit" class="form-control form-control-sm"></form>';
        }
            $print .='          </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>';

    return $print;
}


function getRelatedProducts($products) 
{
    
$output = "<div class=\"container\">
                <div class=\"row\">";

    $count = 4;
    if(count($products) < 4) {
        $count = count($products);
    }

    $keys = count($products) > 0 ? array_rand($products, $count) : [];

    for($i=0; $i<count($keys); $i++) {
    $product = $products[$keys[$i]];
    $arr = dbPhoto($product["StockItemID"]);
    $specialdeal = selectSpecialDealByStockItemID($product["StockItemID"]);
    if (!empty($specialdeal)) {
        $discount = getDiscount($product["RecommendedRetailPrice"], $specialdeal);
    }
    $output .= '<div class="col-sm-3">
    <a style="color: black" href="?page=product&action=show&id='.$product["StockItemID"].'">
    <div class="card border-primary bg-light shadow" style="width: auto;">
        <img class="card-img-top img-fluid" style="height: 190px" src="'.$arr[0]["Path"].'" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title card-title-cap">'.$product["StockItemName"].'</h5>';
    if (empty($specialdeal)) {
        $output .= '<h2 class="card-title">€'.$product["RecommendedRetailPrice"].'</h2>';
    }
    else {
        $output .= '<div class="d-flex justify-content-between" >
                    <h2 class="text-danger m-0" >
                        <s > €'.$product["RecommendedRetailPrice"].'</s >
                    </h2 >
                    <h2 class="text-success" > €'.$discount.'</h2 >
                </div >   ';
    }
    $output .= '
        </div>
        <form method="POST" class=" mb-0">
            <input type="hidden" name="amount" value="1">
            <input type="hidden" name="productID" value="'.$product["StockItemID"].'">
            <button type="submit" name="AddToCart" class="btn btn-success btn-square" style="width: 100%; ">Add to cart</button>
        </form>
    </div>
    </a>
    </div>';
    }
$output .= "</div>
</div>";
return $output;
}

function showProduct($product, $detailed = true, $first = false, $withCarousel = false) 
{
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

    $description = "";
    if(is_array($tags)) {   
        if(count($tags) == 0) {
            $description = "none";
        }

        for ($i = 0; $i < count($tags); $i++) {
            $comma = $i < (count($tags) - 1) ? "," : "";
            $description .= $tags[$i] . $comma;
        }
    }

    $stockAmount = $stock['LastStocktakeQuantity'];
    $outputStock = "";
    $stockClass = "";
    if ($stockAmount == 0) {
        $stockClass = 'danger';
        $outputStock = 'Sold out!';
    } else if ($stockAmount < 100) {
        $stockClass = 'warning';
        $outputStock = 'Be quick! Just a few left';
    } 

    $output = '';

    if($detailed) {
        
        $output .= '<div class="row">
        <div class="col-sm p-2">
            <div id="productImageCarousel" class="carousel slide">
                <h2>'.  $product["StockItemName"] .' </h2>';
                if (!empty($specialdeal)) { 
                    $output .= '<h3 class="text-success">'.  $specialdeal["DealDescription"] .'</h3>';
                }
                $output .= '<div class="carousel-inner">';
                    
                    for ($i = 0; $i < count($images); $i++) {
                        $active = '';
                        if($i == 0) $active = 'active';
                        $output .= '<div class="carousel-item '.  $active .'" data-slide-number="'.  $i .'">
                            <img class="d-block w-100" src="'.  $images[$i]["Path"] .'">
                        </div>';
                    }
                $output .= '</div>';
                if(count($images) > 1) { 
                    $output .= ' 
                    <a class="carousel-control-prev" href="#productImageCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#productImageCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>           
                    <ol class="carousel-indicators">';
                        for ($i = 0; $i < count($images); $i++) {
                            $active = '';
                            if( $i == 0) $active = 'active';
                            $output .= '<li data-target="#productImageCarousel" data-slide-to="'.  $i .'" class="'. $active .'">
                                <img class="d-block" src="'.  $images[$i]["Path"] .'">
                            </li>';
                        }
                    $output .= '</ol>';
                }
                $CoM = '';
                if(isset($customFields) && !empty($customFields->CountryOfManufacture)) {
                    $CoM = $customFields->CountryOfManufacture = '';
                }
            $output .= '</div>
        </div>
        <div class="col-sm d-flex flex-column align-content-*-end">
            <div>
                <h4>'.  $product["MarketingComments"] .'</h4>
                <p><b>Country of manufacture:</b> '. $CoM .'</p>
                <p><b>Specifications:</b> '.  $description .'</p>
                <p><b>Lead time days:</b> '.  $product["LeadTimeDays"] .'</p>
                <p><b>Quantity per outer:</b> '.  $product["QuantityPerOuter"] .'</p>
                <p><b>Weight</b> '.  $product["TypicalWeightPerUnit"] .' kg</p>
                </br>
                <div class="text-'.  $stockClass .'">'.  $outputStock .'</div>
            </div>
            <div>';
                if(empty($specialdeal)) {
                    $output .= '<h1> €'. $product["RecommendedRetailPrice"].'</h1>
                    <h6>'. substr($product["TaxRate"], 0, -1).'% tax rate included</h6>';
                } else {
                   $output .=' <h2 class="text-danger">
                        <s>€'. $product["RecommendedRetailPrice"].'</s>
                    </h2>
                    <h1 class="text-success">€'. $discount.'</h1>';
                } 
                $output .= '
                <br>
                <form method="POST">
                    <input type="hidden" name="productID" value="'.  $product["StockItemID"] .'" >
                    <div class="row">
                        <div class="col-md-2">  
                            <label for="tbxAmount">Aantal:</label>                          
                            <input type="number" id="tbxAmount" name="amount"  class="form-control" value="1">   
                        </div>
                        <div class="col-md-3 my-3">
                            <button type="submit" name="AddToCart" class="btn btn-success">Add tot Cart</button>
                        </div>
                    </div>
                     </form>
            </div>
        </div>
    </div>';

    } else {

        if($withCarousel) {
            $active = '';
            if($first) {
                $active = 'active';
            }
            $output .= '<div class="carousel-item '. $active .'">';
        }
        
        $output .= '
            <div class="card my-2">
                <a class="black-text" href="index.php?page=product&action=show&id='. $product['StockItemID'].'">
                <div class="card-header">
                    <h2>'.  $product["StockItemName"] .' <span class="badge badge-primary">Limited offer</span></h2>
                </div>
                <div class="card-body row">
                    <div class="col-sm p-2">
                        <img class="d-block w-100 h-100" src="'.  $images[0]["Path"] .'">
                    </div>
                    <div class="col-sm d-flex flex-column align-content-*-end">
                        <div>
                            <h4>'.  $product["MarketingComments"] .'</h4>
                            <p><b>Country of manufacture</b>: '.  $customFields->CountryOfManufacture ?? "" .'</p>
                            <p><b>Specifications</b>: '.  $description .'
                            </p>
                            </br>
                            <div class="text-'.  $stockClass .'">'.  $outputStock .'</div>
                        </div>
                        <div>';
                            if (empty($specialdeal)) { 
                                $output .= '<h1> €'.  $product["RecommendedRetailPrice"] .'</h1>';
                            } else {
                                $output .= '<h2 class="text-danger">
                                    <s>€'.  $product["RecommendedRetailPrice"] .'</s>
                                </h2>
                                <h1 class="text-success">€'.  $discount .'</h1>';
                            }
                            
                            $output .= '<br>
                            <form method="POST" class=" mb-0">
                                <input type="hidden" name="amount" value="1">
                                <input type="hidden" name="productID" value="'. $product["StockItemID"] .'">
                                <button type="submit" name="AddToCart" class="btn btn-success">Add to cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </a>';

        if($withCarousel) {
            $output .= '</div>';
        }
    }

    return $output;
} 