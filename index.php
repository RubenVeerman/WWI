<?php
session_start();
require_once "./functions/core.php";
?>

<!DOCTYPE html>
<html lang="nl">
<head>
	<title>Wide World Importers</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="jumbotron text-center" style="margin-bottom:0">
<h1>Online Store</h1>      
    <p>Mission, Vission & Values</p>
</div>

<<<<<<< HEAD
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Logo</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="<?= setWhenActive("home") ? "active" : "" ?>"><a href="?page=home">Home</a></li>
        <li class="<?= setWhenActive("product") ? "active" : "" ?>"><a href="?page=product&action=overview">Products</a></li>
        <li class="<?= setWhenActive("*") ? "active" : "" ?>"><a href="#">Deals</a></li>
        <li class="<?= setWhenActive("*") ? "active" : "" ?>"><a href="#">Stores</a></li>
        <li class="<?= setWhenActive("*") ? "active" : "" ?>"><a href="#">Contact</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Your Account</a></li>
        <li><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>
      </ul>
    </div>
  </div>
=======
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="#">WWI</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item <?= setWhenActive("home") ? "active" : "" ?>">
        <a class="nav-link" href="?page=home">Home</a>
      </li>
      <li class="nav-item <?= setWhenActive("home") ? "active" : "" ?>"">
        <a class="nav-link" href="?page=product&action=overview">Producten</a>
      </li>
      <li class="nav-item <?= setWhenActive("home") ? "active" : "" ?>"">
        <a class="nav-link" href="#">Link</a>
      </li>    
    </ul>
  </div>  
>>>>>>> 38a46969cc1b5c54fad5873dc6658b36ea13ea54
</nav>

<div class="container" style="margin-top:30px">
<?= startSite(); ?>
  </div>

<div class="jumbotron text-center" style="margin-bottom:0">
  <p>Footer</p>
</div>

</body>
</html>