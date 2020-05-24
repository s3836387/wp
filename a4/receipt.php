<!DOCTYPE html>
<html lang='en'>
<!--
   Author: Ngo QUang Khai
 -->

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Assignment 4 | Receipt</title>

  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <!-- Keep wireframe.css for debugging, add your css to style.css -->
  <link id='wireframecss' type="text/css" rel="stylesheet" href="../wireframe.css" disabled>
  <link id='stylecss' type="text/css" rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="mystyle.css">
  <script src='../wireframe.js'></script>
  <?php
  require 'tools.php';
  if (empty($_SESSION)) {
    header("Location: index.php");
  }
  $now = date('d/m h:i');
  $cartid = 0;
  $cusName = $_SESSION["cart"]['cust']['name'];
  $cusMobile = $_SESSION["cart"]['cust']['mobile'];
  $cusEmail = $_SESSION["cart"]['cust']['email'];
  $total = $_SESSION["cart"]['total'];
  $seats = $_SESSION["cart"]['seats'];
  $movie = $_SESSION["cart"]['movie'];
  $row = array_merge(
    [$now],
    [$cartid],
    [$cusName],
    [$cusEmail],
    [$cusMobile],
    $_SESSION["cart"]['movie'],
    $_SESSION["cart"]['seats'],
    [$total]
  );

  ?>
</head>

<body>

  <header>
    <div id="logo">Cinemax</div>
  </header>

  <main>

    <!--Navigation area-->
    <nav id="navbar" class="navbar justify-content-center sticky-top">
      <!--Links-->
      <a class="navbar-brand active" href="#home">Home</a>
      <a class="navbar-brand" href="#aboutUs">About Us</a>
      <a class="navbar-brand" href="#nowShowing">Now Showing</a>
      <a class="navbar-brand" href="#prices">Prices</a>
    </nav>


    <script src="script.js"></script>
  </main>

  <footer>
    <div>&copy;
      <script>
        document.write(new Date().getFullYear());
      </script> Ngo Quang Khai, s3836387 , githut link: https://github.com/s3836387/wp. Last modified
      <?= date("Y F d  H:i", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>.</div>
    <div>Disclaimer: This website is not a real website and is being developed as part of a School of Science Web
      Programming course at RMIT University in Melbourne, Australia.</div>
    <div><button id='toggleWireframeCSS' onclick='toggleWireframe()'>Toggle Wireframe CSS</button></div>
    <?php
    echo "<h4>POST</h4>";
    preShow($_POST);
    echo "<h4>GET</h4>";
    preShow($_GET);
    echo "<h4>SESSION</h4>";
    preShow($_SESSION);
    ?>
    <h4>POST code</h4>
    <?php printMyCode();
    ?>
  </footer>

</body>

</html>