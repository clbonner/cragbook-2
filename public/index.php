<!DOCTYPE html>
<?php include(__DIR__ ."/../src/Cragbook/Cragbook.php") ?>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/bootstrap.css">
  <script src="https://maps.googleapis.com/maps/api/js?key=<?= $GOOGLE_MAPS_API_KEY ?>"></script>
  <script type="text/javascript" src="/js/jquery/jquery-3.5.1.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap/bootstrap.bundle.min.js"></script>
  <script type="module" src="/js/cragbook/cragbook.js"></script>
  <title><?= $SITE_TITLE ?></title>
</head>
<body>
  <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
    <div class="navbar-brand">
      <img id="home" src="/cragbook.png" alt="Cragbook" style="height:50px;" role="button">
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navhide" aria-controls="navhide"
      aria-expanded="false">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navhide" class="collapse navbar-collapse">
      <div class="container justify-content-sm-start m-0">
        <ul class="navbar-nav">
          <li class="navbar-text">
            <a class="nav-link" id="guides" role="button">Guides</a>
          </li>
          <li class="navbar-text">
            <a class="nav-link" id="areas" role="button">Areas</a>
          </li>
          <li class="navbar-text">
            <a class="nav-link" id="crags" role="button">Crags</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="container justify-content-sm-end m-0">
      <input type="text" id="search" placeholder="Search">
    </div>
  </nav>
  <div id="view">
  </div>
</body>
</html>