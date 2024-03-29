<?php
session_start();
require_once "./functions/core.php";
require_once "./functions/authfunctions.php";
require_once "./functions/databaseFunctions.php";
startAuth();
prepareCart();

// echo "<pre>";
// var_dump($_SESSION["Cart"]);
// echo "</pre>";

if(isset($_SESSION['userName'])) {
    $peopleInfo = selectOnePeople($_SESSION['userName']);
}

$content = startSite();
$footer = getFooter();

?>
<!DOCTYPE html>

<html lang="nl">

<head>
	<title>Wide World Importers</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font awesome library   -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <!-- Jquery ui -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" />
    <!-- Own CSS files -->
    <link href="./public/css/main.css" rel="stylesheet" type="text/css">
</head>
<body>
<header class="text-center wwi-header mb-0">

    <h1><span class="logo-text">Wide World</span> <span class="logo-imp">Importers</span></h1>

    <p class="logo-imp m-0">Mission, Vission & Values</p>

</header>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark mt-0">
  <a class="navbar-brand <?= setWhenActive("home", LVL_NAV) ?>" href="?page=home">WWI</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse row" id="collapsibleNavbar">
    <ul class="navbar-nav col-sm-3">
      <li class="nav-item <?= setWhenActive("home", LVL_NAV) ?>">
        <a class="nav-link" href="?page=home">Home</a>
      </li>
      <li class="nav-item <?= setWhenActive("product", LVL_NAV) ?>">
        <a class="nav-link" href="?page=product&action=overview">Products</a>
      </li>

        <?php
        if(isset($_SESSION['userName'])) {
            if($peopleInfo['IsSalesperson'] == 1 || $peopleInfo['IsSystemUser'] == 1 || $peopleInfo['IsEmployee'] == 1){
        ?>
            <li class="nav-item <?= setWhenActive("user", LVL_NAV) ?>">
                <a class="nav-link" href="?page=user&action=overview">Users</a>
            </li>
        <?php
            }
        }
        ?>
    </ul>
      <form method="get" action="index.php" class="col-sm-5">
          <input type="hidden" name="page" value="product">
          <input type="hidden" name="action" value="overview">
          <div class="input-group">
                  <input id="searchBar" type="text" class="form-control" placeholder="Search product" name="searchInput">
                  <div class="input-group-append">
                      <button class="btn btn-secondary" type="submit">
                          <i class="fa fa-search"></i>
                      </button>
                  </div>
              <script>
                  
              </script>
          </div>
      </form>
      <ul class="navbar-nav col-sm-4 justify-content-end">
          <li class="nav-item <?= setWhenActive("auth.registration", LVL_NAV) ?>">
              <?php
          if(isset( $_SESSION['userName'])){
                      echo '<a class="nav-link" href="?page=auth&action=profile">' . $peopleInfo["PreferredName"] . '</a>';

              }
              else{
                  echo '<a class="nav-link" href="?page=auth&action=registration">Registration</a>';
              }
              ?>

          </li>
          <li class="nav-item <?= setWhenActive("auth.login", LVL_NAV) ?>">
          <?php
          if(isset( $_SESSION['userName'])){
                  echo '<form method="post"><button class="btn btn-default nav-link" type="submit" name="submit_logoff">Log off</button></form>';
          }
          else{
              echo '<a class="nav-link" href="?page=auth&action=login">Sign in</a>';
          }
          ?>
          </li>
          <li><a class="nav-link" href="?page=cart"><i class="fas fa-shopping-cart"></i> <?= count($_SESSION["Cart"]) > 0 ? "(".count($_SESSION["Cart"]).")" : "" ?></a></li>
      </ul>
  </div>
</nav>



<div class="container-fluid content" style="margin-top:30px">

    <?= $content ?>

</div>

<?=$footer;?>

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
        crossorigin="anonymous"></script>

<!-- Own Javascript files -->
<script src="./public/js/main.js"></script>
  
</body>

</html>