<?php
function getPaginationBar($total_products, $limit, $pn, $currentcategory, $pagelimit) {
    $total_pages = ceil($total_products / $limit);
    $pagLink = "";
    if($total_pages != 1) {
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $pn)
                $pagLink .= "<li class='page-item active'><span class='page-link'>$i<span class='sr-only'>(current)</span></span></li>";
            else
                $pagLink .= "<li class='page-item'><a class='page-link' href='?page=product&action=overview&pageno=$i$currentcategory$pagelimit'>$i</a></li>";
        };
    }

    $dropdownLink = '?page=product&action=overview&pageno=1' . $currentcategory;

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
                    <a class="dropdown-item" href="'. $dropdownLink . '&limit=15">15</a>
                    <a class="dropdown-item" href="'. $dropdownLink . '&limit=30">30</a>
                    <a class="dropdown-item" href="'. $dropdownLink . '&limit=60">60</a>
                    <a class="dropdown-item" href="'. $dropdownLink . '&limit=90">90</a>
                    
                        <form method="get">
                        <input type="hidden" name="page" value="product">
                        <input type="hidden" name="action" value="overview">
                        <input type="hidden" name="pageno" value="1">
                        <input type="hidden" name="'. $catname .'" value="'. $cat .'">
                        
                        <div class="form-row m-1">
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" min="10" placeholder="Num..." name="limit">
                            </div>
                            <div class="col-6">
                                <input type="submit" value="Submit" class="form-control form-control-sm"></form>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>';

    return $print;
}