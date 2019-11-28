<?php
session_start();
require_once "./functions/core.php";
require_once "./functions/databaseFunctions.php";
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
    <!-- Jquery ui -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" />
    <!-- Own CSS files -->
    <link href="./public/css/main.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="jumbotron text-center" style="margin-bottom:0">

    <h1>Wide World Importers</h1>

    <p>Mission, Vission & Values</p>

</div>



<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand <?= setWhenActive("home") ? "active" : "" ?>" href="?page=home">WWI</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse row" id="collapsibleNavbar">
    <ul class="navbar-nav col-sm-3">
      <li class="nav-item <?= setWhenActive("home") ? "active" : "" ?>">
        <a class="nav-link" href="?page=home">Home</a>
      </li>
      <li class="nav-item <?= setWhenActive("product") ? "active" : "" ?>"">
        <a class="nav-link" href="?page=product&action=overview">Producten</a>
      </li>
      <li class="nav-item <?= setWhenActive("home") ? "active" : "" ?>"">
        <a class="nav-link" href="#">Link</a>
      </li>    
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
          <li class="nav-item <?= setWhenActive("auth.registration") ? "active" : "" ?>"">
          <a class="nav-link" href="?page=auth&action=registration">Registration</a>
          </li>
          <li class="nav-item <?= setWhenActive("auth.login") ? "active" : "" ?>"">
          <a class="nav-link" href="?page=auth&action=login">Sign in</a>
          </li>
      </ul>
  </div>
</nav>



<div class="container-fluid" style="margin-top:30px">

    <?= startSite(); ?>

</div>



<div class="jumbotron text-center" style="margin-bottom:0">

    <p>Footer</p>

</div>


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