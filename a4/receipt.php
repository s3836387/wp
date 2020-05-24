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
  <link rel="stylesheet" href="navbar.css">
  <script src='../wireframe.js'></script>
  <?php
  require 'tools.php';
  if (empty($_SESSION)) {
    header("Location: index.php");
  }

  $now = date('d/m h:i');
  $cartid = date('hi');
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
  $myfile = fopen("bookings.csv", "a");
  fputcsv($myfile, $row);
  fclose($myfile);
  ?>
  <script>
    function printInvoice(eid) {
      let original = document.body.innerHTML
      let toPrint = document.getElementById(eid).innerHTML
      console.log(toPrint)
      document.body.innerHTML = toPrint
      window.print()
      document.body.innerHTML = original
    }
  </script>
</head>

<body>

  <header>
    <div id="logo">Cinemax</div>
    <!--Navigation area-->
    <nav id="navbar" class="navbar justify-content-center sticky-top">
      <!--Links-->
      <a class="navbar-brand active" href="#home">Home</a>
      <a class="navbar-brand" href="#aboutUs">About Us</a>
      <a class="navbar-brand" href="#nowShowing">Now Showing</a>
      <a class="navbar-brand" href="#prices">Prices</a>
    </nav>
  </header>

  <main>
    <section id='invoice'>
    <div class="container-fluid row justify-content-center section-header" > 
        <h2>Your Invoice </h2>
      </div>
      <div class="container">
        <div class="card" id='invoice'>
          <div class="card-header">
            Invoice
            <strong><?php echo $now ?></strong>
          </div>
          <div class="card-body">
            <!------Invoice head------->
            <div class="row mb-4">
              <div class="col-sm-6">
                <h6 class="mb-3">From:</h6>
                <div>
                  <strong>Cinemax cinema</strong>
                </div>
                <div>71-101 Kangaroo, Australia</div>
                <div>Email: info@cinemax.com.php</div>
                <div>Phone: 04 1234 3333</div>
                <div>ABN number: 00 123 456 789 </div>
              </div>

              <div class="col-sm-6">
                <h6 class="mb-3">To:</h6>
                <div>
                  <strong><?php echo $cusName ?></strong>
                </div>
                <div>Email: <?php echo $cusEmail ?></div>
                <div>Phone: <?php echo $cusMobile ?></div>
              </div>
            </div>

            <div class="table-responsive-sm">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="center">#</th>
                    <th>Seat</th>
                    <th>Description</th>

                    <th class="right">Unit Cost</th>
                    <th class="center">Qty</th>
                    <th class="right">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  printInvoiceRow($seats, $movie['day'], $movie['hour']);
                  ?>
                </tbody>
              </table>
            </div>
            <div class="row">
              <div class="col-lg-4 col-sm-5">
              </div>

              <div class="col-lg-4 col-sm-5 ml-auto">
                <table class="table table-clear">
                  <tbody>
                    <tr>
                      <td class="left">
                        <strong>Subtotal</strong>
                      </td>
                      <td class="right">$ <?php echo $total ?></td>
                    </tr>
                    <tr>
                      <td class="left">
                        <strong>VAT (10%)</strong>
                      </td>
                      <td class="right">$ <?php echo $total * 0.1 ?></td>
                    </tr>
                    <tr>
                      <td class="left">
                        <strong>Total</strong>
                      </td>
                      <td class="right">
                        <strong>$ <?php echo $total * 1.1 ?></strong>
                      </td>
                    </tr>
                  </tbody>
                </table>

              </div>

            </div>

          </div>
        </div>
        <button type='button' class='btn btn-primary float-right' onclick="printInvoice('invoice')">Print</button>
      </div>
    </section>
    <section id="ticket">
      <div class="container-fluid row justify-content-center section-header" > 
        <h2>Your ticket </h2>
      </div>
    <div class="container">
        <div class="card" id='invoice'>
          <div class="card-header">
            Ticket (group):
            <strong><?php echo $movie['id'] ?></strong>
          </div>
          <div class="card-body">
            <!------Invoice head------->
            <div class="row mb-4">
              <div class="col-sm-6">
                <h6 class="mb-3">Screening</h6>

                <div class="container align-items-center"><strong><?php echo getScreen($movie['id']) ?></strong></div>
              </div>

              <div class="col-sm-6">
                <h6 class="mb-3">Movie Info</h6>
                <div>Movie day: <?php echo getMovieday($movie['day']) ?></div>
                <div>Movie Time: <?php echo getMovieTime($movie['hour']) ?></div>
              </div>
            </div>

            <div class="table-responsive-sm">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="center">#</th>
                    <th>Seat</th>
                    <th>Description</th>
                    <th class="center">Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  printTicketRow($seats, $movie['day'], $movie['hour']);
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <button type='button' class='btn btn-primary float-right' onclick="printInvoice('invoice')">Print</button>
      </div>
    </section>

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

  </footer>

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
</body>

</html>