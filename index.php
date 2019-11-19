<?php
session_start();
require_once "./functions/core.php";
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <style>
        .ui-autocomplete {
            max-height: 30%;
            overflow-y: auto;   /* prevent horizontal scrollbar */
            overflow-x: hidden; /* add padding to account for vertical scrollbar */
            z-index:1000 !important;
        }
    </style>
	<title>Wide World Importers</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font awesome library   -->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" />
</head>
<body>
<div class="jumbotron text-center" style="margin-bottom:0">
<h1>Online Store</h1>      
    <p>Mission, Vission & Values</p>
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="#">WWI</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse row" id="collapsibleNavbar">
    <ul class="navbar-nav col-sm-3">
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
      <form method="get" action="index.php" class="col-sm-5">
          <input type="hidden" name="page" value="product">
          <div class="input-group">
                  <input id="searchBar" type="text" class="form-control" placeholder="Search this blog" name="searchInput">
                  <div class="input-group-append">
                      <button class="btn btn-secondary" type="submit">
                          <i class="fa fa-search"></i>
                      </button>
                  </div>
              <script>
                  $("#searchBar").on("input", function ()
                  {
                      $("#searchBar").autocomplete({
                          source: function (request, response){
                                  $.get(`./api.php?query=${request.term}`, function(data) {
                                      console.log(data);
                                      data = JSON.parse(data);
                                      console.log(data);
                                      response(data);
                                  });
                        }
                      });
                  });
              </script>
          </div>
      </form>
  </div>
</nav>

<div class="container" style="margin-top:30px">
<?= startSite(); ?>
  </div>

<div class="jumbotron text-center" style="margin-bottom:0">
  <p>Footer</p>
</div>

</body>
</html>